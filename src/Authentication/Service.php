<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Service as TrustedAppService;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Response;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use OutOfBoundsException;
use Throwable;
use function in_array;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Authentication
 */
class Service extends
    AbstractService
{
    public const JWT_ISSUER = 'stalactite.auth-api';

    private const AUTHORIZED_JWT_TYPES = [
        'user',
        'customer'
    ];

    private $serviceTrustedApp;

    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * trustedApp
     *
     * @return TrustedAppService
     */
    public function trustedApps(): TrustedAppService
    {
        if (null === $this->serviceTrustedApp) {
            $this->serviceTrustedApp = new TrustedAppService($this->getClient());
        }

        return $this->serviceTrustedApp;
    }

    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * getRSAPublicKey
     *
     * get the Stalactite API RSA public key
     *
     * @return string
     *
     * @throws ClientException
     */
    public function getRSAPublicKey(): string
    {
        //TODO: uniformize
        try {
            return $this
                ->getClient()
                ->getHttpClient()
                ->request(
                    'GET',
                    '/auth/publicKey'
                )
                ->getContent();
        } catch (Throwable $throwable) {
            $exception = new ClientException(
                'Error while fetching Stalactite API RSA public key',
                ClientException::CLIENT_TRANSPORT,
                $throwable
            );

            $this
                ->getLogger()
                ->error($exception);

            throw $exception;
        }
    }

    /**
     * validate
     *
     * check if the given JWT is a valid Stalactite API JWT
     *
     * @param string $jwt
     *
     * @return bool
     *
     * @throws OutOfBoundsException
     * @throws ClientException
     */
    public function validate(
        string $jwt
    ): bool
    {
        try {
            $token = $this->getTokenFromString($jwt);
        } catch (Throwable $throwable) {
            $exception = new ClientException(
                'Invalid user JWT',
                ClientException::INVALID_JWT_STRING,
                $throwable
            );

            $this->getLogger()
                ->error($exception);

            throw $exception;
        }

        $data = new ValidationData();
        $data->setIssuer(self::JWT_ISSUER);

        if (!$token->hasClaim('iss') || !$token->hasClaim('aud') || !$token->hasClaim('type') ||
            !$token->hasClaim('sub') || !$token->hasClaim('iat') || !$token->hasClaim('exp')) {
            $exception = new ClientException(
                'Invalid JWT structure',
                ClientException::INVALID_JWT_STRUCTURE
            );

            $this->getLogger()
                ->error($exception);

            throw $exception;
        }

        if ($token->isExpired()) {
            $exception = new ClientException(
                'Expired JWT',
                ClientException::EXPIRED_JWT
            );

            $this->getLogger()
                ->error($exception);

            throw $exception;
        }

        if (!in_array($token->getClaim('type'), self::AUTHORIZED_JWT_TYPES, true)) {
            $exception = new ClientException(
                'Invalid JWT user type',
                ClientException::INVALID_JWT_USER_TYPE
            );

            $this->getLogger()
                ->error($exception);

            throw $exception;
        }

        if (!$token->validate($data)) {
            $exception = new ClientException(
                'Invalid JWT issuer',
                ClientException::INVALID_JWT_ISSUER
            );

            $this->getLogger()
                ->error($exception);

            throw $exception;
        }

        $signer = new Sha256();
        $publicKey = new Key($this->getRSAPublicKey());

        try {
            return $token->verify($signer, $publicKey);
        } catch (InvalidArgumentException $exception) {
            // thrown by the library on invalid key
            $exception = new ClientException(
                'Invalid RSA public key',
                ClientException::INVALID_STALACTITE_RSA_PUBLIC_KEY,
                $exception
            );

            $this->getLogger()
                ->error($exception);

            throw $exception;
        } catch (Throwable $throwable) { // other exceptions result in an invalid token / signature
            $exception = new ClientException(
                'Invalid JWT signature',
                ClientException::INVALID_JWT_SIGNATURE,
                $throwable
            );

            $this->getLogger()
                ->error($exception);

            throw $exception;
        }
    }

    /**
     * login
     *
     * @param TrustedApp $trustedAppModel
     * @param string $userGoogleJwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidSchemaException
     * @throws InvalidDataTypeException
     */
    public function login(
        TrustedApp $trustedAppModel,
        string $userGoogleJwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'jwt' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->post(
                '/auth/login',
                [
                    'json' => [
                        'appName' => $trustedAppModel->getName(),
                        'appToken' => $trustedAppModel->getAuthToken(),
                        'userGoogleJwt' => $userGoogleJwt,
                    ],
                ],
                $schema
            );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'jwt' => $response['jwt']
            ]
        );
    }

    /**
     * @param string $jwt
     *
     * @return Token
     * @throws Throwable
     */
    protected function getTokenFromString(string $jwt): Token
    {
        return (new Parser())->parse($jwt);
    }
}
