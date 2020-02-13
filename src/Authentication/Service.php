<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication;

use BadMethodCallException;
use hunomina\Validator\Json\Rule\JsonRule;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Service as TrustedAppService;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
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
    
    private $serviceTrustedApp;
    
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
    public function trustedApps() : TrustedAppService
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
            $this
                ->getLogger()
                ->error($throwable);
            
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
            $exception = new ClientException(
                'Invalid JWT structure',
                ClientException::INVALID_JWT_STRUCTURE
            );
            
            $this
                ->getLogger()
                ->error($exception);
            
            throw $exception;
        }
        
        if ($token->isExpired()) {
            $exception = new ClientException(
                'Expired JWT',
                ClientException::EXPIRED_JWT
            );
            
            $this
                ->getLogger()
                ->error($exception);
            
            throw $exception;
        }
        
        if (!in_array($token->getClaim('type'), self::AUTHORIZED_JWT_TYPES, true)) {
            $exception = new ClientException(
                'Invalid JWT user type',
                ClientException::INVALID_JWT_USER_TYPE
            );
            
            $this
                ->getLogger()
                ->error($exception);
            
            throw $exception;
        }
        
        if (!$token->validate($data)) {
            $exception = new ClientException(
                'Invalid JWT issuer',
                ClientException::INVALID_JWT_ISSUER
            );
            
            $this
                ->getLogger()
                ->error($exception);
            
            throw $exception;
        }
        
        $signer    = new Sha256();
        $publicKey = new Key($this->getRSAPublicKey());
        
        try {
            return $token->verify($signer, $publicKey);
        } catch (BadMethodCallException $badMethodCallException) {
            $this
                ->getLogger()
                ->error($badMethodCallException);
    
            // thrown by the library on invalid key
            throw new ClientException(
                'Unsigned token',
                ClientException::INVALID_JWT_SIGNATURE,
                $badMethodCallException
            );
        } catch (InvalidArgumentException $invalidArgumentException) {
            $this
                ->getLogger()
                ->error($invalidArgumentException);
            
            // thrown by the library on invalid key
            throw new ClientException(
                'Invalid RSA public key',
                ClientException::INVALID_STALACTITE_RSA_PUBLIC_KEY,
                $invalidArgumentException
            );
        } catch (Throwable $throwable) { // other exceptions result in an invalid token / signature
            $this
                ->getLogger()
                ->error($throwable);
            
            throw new ClientException(
                'Invalid JWT signature',
                ClientException::INVALID_JWT_SIGNATURE,
                $throwable
            );
        }
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
                ->getClient()
                ->getHttpClient()
                ->request(
                    'GET',
                    '/auth/publicKey'
                )
                ->getContent();
        } catch (Throwable $throwable) {
            $this
                ->getLogger()
                ->error($throwable);
            
            throw new ClientException(
                'Error while fetching Stalactite API RSA public key',
                ClientException::CLIENT_TRANSPORT,
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function login(
        TrustedApp $trustedAppModel,
        string $userGoogleJwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/auth/login',
                    'POST'
                ))
                    ->setJson(
                        [
                            'appName'       => $trustedAppModel->getName(),
                            'appToken'      => $trustedAppModel->getAuthToken(),
                            'userGoogleJwt' => $userGoogleJwt,
                        ]
                    )
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'jwt' => $response['jwt'],
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'jwt' => [
                                'type' => JsonRule::STRING_TYPE,
                                'null' => true,
                            ],
                        ]
                    )
            );
    }
}
