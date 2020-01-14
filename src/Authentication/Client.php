<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\Authentication;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use InvalidArgumentException;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\Response;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Throwable;

class Client extends
    AbstractClient
{
    public const JWT_ISSUER = 'stalactite.auth-api';
    
    public const  API_URL_PREFIX       = '/auth';
    private const AUTHORIZED_JWT_TYPES = [
        'user',
        'customer'
    ];
    
    /** @var TrustedAppClient $trustedAppClient */
    private $trustedAppClient;
    
    /**
     * @return string
     * @throws ClientException
     * Get the Stalactite API RSA public key
     */
    public function getRSAPublicKey() : string
    {
        try {
            return $this
                ->getHttpClient()
                ->request(
                    'GET',
                    $this->apiHost . self::API_URL_PREFIX . '/publicKey'
                )
                ->getContent();
        } catch (Throwable $throwable) {
            throw new ClientException(
                'Error while fetching Stalactite API RSA public key',
                ClientException::CLIENT_TRANSPORT_ERROR,
                $throwable
            );
        }
    }
    
    /**
     * @param string $jwt
     *
     * @return bool
     * @throws ClientException
     * Check if the given JWT is a valid Stalactite API JWT
     */
    public function validate(string $jwt) : bool
    {
        try {
            $token = $this->getTokenFromString($jwt);
        } catch (Throwable $t) {
            throw new ClientException('Invalid user JWT', ClientException::INVALID_JWT_STRING_ERROR, $t);
        }
        
        $data = new ValidationData();
        $data->setIssuer(self::JWT_ISSUER);
        
        if (!$token->hasClaim('iss') || !$token->hasClaim('aud') || !$token->hasClaim('type') ||
            !$token->hasClaim('sub') || !$token->hasClaim('iat') || !$token->hasClaim('exp')) {
            throw new ClientException('Invalid JWT structure', ClientException::INVALID_JWT_STRUCTURE_ERROR);
        }
        
        if ($token->isExpired()) {
            throw new ClientException('Expired JWT', ClientException::EXPIRED_JWT_ERROR);
        }
        
        if (!in_array($token->getClaim('type'), self::AUTHORIZED_JWT_TYPES, true)) {
            throw new ClientException('Invalid JWT user type', ClientException::INVALID_JWT_USER_TYPE_ERROR);
        }
        
        if (!$token->validate($data)) {
            throw new ClientException('Invalid JWT issuer', ClientException::INVALID_JWT_ISSUER_ERROR);
        }
        
        $signer    = new Sha256();
        $publicKey = new Key($this->getRSAPublicKey());
        
        try {
            return $token->verify($signer, $publicKey);
        } catch (InvalidArgumentException $e) { // thrown by the library on invalid key
            throw new ClientException('Invalid RSA public key', ClientException::INVALID_STALACTITE_RSA_PUBLIC_KEY_ERROR);
        } catch (Throwable $t) { // other exceptions result in an invalid token / signature
            throw new ClientException('Invalid JWT signature', ClientException::INVALID_JWT_SIGNATURE_ERROR);
        }
    }
    
    /**
     * @param TrustedApp $trustedApp
     * @param string     $userGoogleJwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function login(TrustedApp $trustedApp, string $userGoogleJwt) : Response
    {
        $data = [
            'appName'       => $trustedApp->getName(),
            'appToken'      => $trustedApp->getAuthToken(),
            'userGoogleJwt' => $userGoogleJwt
        ];
        
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
        
        $r = $this->requestPost(
            $this->apiHost . self::API_URL_PREFIX . '/login',
            ['json' => $data],
            $schema
        );
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'jwt' => $r['jwt']
            ]
        );
    }
    
    /**
     * @return TrustedAppClient
     */
    public function trustedApps() : TrustedAppClient
    {
        if (!($this->trustedAppClient instanceof TrustedAppClient)) {
            $this->trustedAppClient = new TrustedAppClient($this->apiHost, $this->userAgent);
        }
        
        return $this->trustedAppClient;
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
