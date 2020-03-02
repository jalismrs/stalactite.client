<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication;

use BadMethodCallException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Service as TrustedAppService;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
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

    /**
     * @var TrustedAppService|null
     */
    private ?TrustedAppService $serviceTrustedApp = null;

    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * trustedApps
     *
     * @return TrustedAppService
     */
    public function trustedApps(): TrustedAppService
    {
        if ($this->serviceTrustedApp === null) {
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
     * @param string $jwt
     * @return bool
     * @throws ClientException
     * @throws ServiceException
     */
    public function validateJwt(string $jwt): bool
    {
        try {
            $token = self::getTokenFromString($jwt);
        } catch (Throwable $throwable) {
            $this
                ->getLogger()
                ->error($throwable);

            throw new ServiceException(
                'Invalid user JWT',
                ClientException::INVALID_JWT_STRING,
                $throwable
            );
        }

        $data = new ValidationData();
        $data->setIssuer(self::JWT_ISSUER);

        if (
            !$token->hasClaim('iss')
            || !$token->hasClaim('aud')
            || !$token->hasClaim('type')
            || !$token->hasClaim('sub')
            || !$token->hasClaim('iat')
            || !$token->hasClaim('exp')
        ) {
            $exception = new ServiceException(
                'Invalid JWT structure',
                ClientException::INVALID_JWT_STRUCTURE
            );

            $this
                ->getLogger()
                ->error($exception);

            throw $exception;
        }

        if ($token->isExpired()) {
            $exception = new ServiceException(
                'Expired JWT',
                ClientException::EXPIRED_JWT
            );

            $this
                ->getLogger()
                ->error($exception);

            throw $exception;
        }

        try {
            if (!in_array($token->getClaim('type'), self::AUTHORIZED_JWT_TYPES, true)) {
                $exception = new ServiceException(
                    'Invalid JWT user type',
                    ClientException::INVALID_JWT_USER_TYPE
                );

                $this
                    ->getLogger()
                    ->error($exception);

                throw $exception;
            }
        } catch (OutOfBoundsException $outOfBoundsException) {
            $this
                ->getLogger()
                ->error($outOfBoundsException);

            throw new ServiceException(
                'should never happen',
                ClientException::INVALID_JWT_STRUCTURE,
                $outOfBoundsException
            );
        }

        if (!$token->validate($data)) {
            $exception = new ServiceException(
                'Invalid JWT issuer',
                ClientException::INVALID_JWT_ISSUER
            );

            $this
                ->getLogger()
                ->error($exception);

            throw $exception;
        }

        $signer = new Sha256();
        $publicKey = new Key($this->getRSAPublicKey());

        try {
            return $token->verify($signer, $publicKey);
        } catch (BadMethodCallException $badMethodCallException) {
            $this
                ->getLogger()
                ->error($badMethodCallException);

            // thrown by the library on invalid key
            throw new ServiceException(
                'Unsigned token',
                ClientException::INVALID_JWT_SIGNATURE,
                $badMethodCallException
            );
        } catch (InvalidArgumentException $invalidArgumentException) {
            $this
                ->getLogger()
                ->error($invalidArgumentException);

            // thrown by the library on invalid key
            throw new ServiceException(
                'Invalid RSA public key',
                ClientException::INVALID_STALACTITE_RSA_PUBLIC_KEY,
                $invalidArgumentException
            );
        } catch (Throwable $throwable) { // other exceptions result in an invalid token / signature
            $this
                ->getLogger()
                ->error($throwable);

            throw new ServiceException(
                'Invalid JWT signature',
                ClientException::INVALID_JWT_SIGNATURE,
                $throwable
            );
        }
    }

    /**
     * @param string $jwt
     * @return Token
     */
    private static function getTokenFromString(string $jwt): Token
    {
        return (new Parser())->parse($jwt);
    }

    /**
     * @return Response
     * @throws ClientException
     */
    public function getRSAPublicKey(): Response
    {
        $endpoint = new Endpoint('/auth/publicKey');
        return $this->getClient()->request($endpoint);
    }

    /**
     * @param TrustedApp $trustedAppModel
     * @param string $userGoogleJwt
     * @return Response
     * @throws ClientException
     */
    public function login(TrustedApp $trustedAppModel, string $userGoogleJwt): Response
    {
        $endpoint = new Endpoint('/auth/login', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema([
            'jwt' => ['type' => JsonRule::STRING_TYPE]
        ]));

        return $this
            ->getClient()
            ->request($endpoint, [
                'json' => [
                    'appName' => $trustedAppModel->getName(),
                    'appToken' => $trustedAppModel->getAuthToken(),
                    'userGoogleJwt' => $userGoogleJwt,
                ]
            ]);
    }
}
