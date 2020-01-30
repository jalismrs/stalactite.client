<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Client as TrustedAppClient;
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
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Authentication
 */
class Client extends
    AbstractClient
{
    public const JWT_ISSUER = 'stalactite.auth-api';
    
    private const AUTHORIZED_JWT_TYPES = [
        'user',
        'customer'
    ];
    
    private $clientTrustedApp;
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    /**
     * trustedApp
     *
     * @return TrustedAppClient
     */
    public function trustedApps() : TrustedAppClient
    {
        if (null === $this->clientTrustedApp) {
            $this->clientTrustedApp = new TrustedAppClient($this->getHost());
            $this->clientTrustedApp
                ->setHttpClient($this->getHttpClient())
                ->setUserAgent($this->getUserAgent());
        }
        
        return $this->clientTrustedApp;
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
    public function getRSAPublicKey() : string
    {
        //TODO: uniformize
        try {
            return $this
                ->getHttpClient()
                ->request(
                    'GET',
                    $this->getHost() . '/auth/publicKey'
                )
                ->getContent();
        } catch (Throwable $throwable) {
            throw new ClientException(
                'Error while fetching Stalactite API RSA public key',
                ClientException::CLIENT_TRANSPORT,
                $throwable
            );
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
    ) : bool {
        try {
            $token = $this->getTokenFromString($jwt);
        } catch (Throwable $throwable) {
            throw new ClientException(
                'Invalid user JWT',
                ClientException::INVALID_JWT_STRING,
                $throwable
            );
        }
        
        $data = new ValidationData();
        $data->setIssuer(self::JWT_ISSUER);
        
        if (!$token->hasClaim('iss') || !$token->hasClaim('aud') || !$token->hasClaim('type') ||
            !$token->hasClaim('sub') || !$token->hasClaim('iat') || !$token->hasClaim('exp')) {
            throw new ClientException(
                'Invalid JWT structure',
                ClientException::INVALID_JWT_STRUCTURE
            );
        }
        
        if ($token->isExpired()) {
            throw new ClientException(
                'Expired JWT',
                ClientException::EXPIRED_JWT
            );
        }
        
        if (!in_array($token->getClaim('type'), self::AUTHORIZED_JWT_TYPES, true)) {
            throw new ClientException(
                'Invalid JWT user type',
                ClientException::INVALID_JWT_USER_TYPE
            );
        }
        
        if (!$token->validate($data)) {
            throw new ClientException(
                'Invalid JWT issuer',
                ClientException::INVALID_JWT_ISSUER
            );
        }
        
        $signer    = new Sha256();
        $publicKey = new Key($this->getRSAPublicKey());
        
        try {
            return $token->verify($signer, $publicKey);
        } catch (InvalidArgumentException $exception) {
            // thrown by the library on invalid key
            throw new ClientException(
                'Invalid RSA public key',
                ClientException::INVALID_STALACTITE_RSA_PUBLIC_KEY,
                $exception
            );
        } catch (Throwable $throwable) { // other exceptions result in an invalid token / signature
            throw new ClientException(
                'Invalid JWT signature',
                ClientException::INVALID_JWT_SIGNATURE,
                $throwable
            );
        }
    }
    
    /**
     * login
     *
     * @param TrustedApp $trustedAppModel
     * @param string     $userGoogleJwt
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
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'jwt'     => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );
        
        $response = $this->post(
            '/auth/login',
            [
                'json' => [
                    'appName'       => $trustedAppModel->getName(),
                    'appToken'      => $trustedAppModel->getAuthToken(),
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
    protected function getTokenFromString(string $jwt) : Token
    {
        return (new Parser())->parse($jwt);
    }
}
