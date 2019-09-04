<?php

namespace jalismrs\Stalactite\Client\Authentication;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use InvalidArgumentException;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Throwable;

class Client extends AbstractClient
{
    public const JWT_ISSUER = 'stalactite.auth-api';

    public const API_URL_PREFIX = '/auth';
    private const AUTHORIZED_JWT_TYPES = ['user', 'customer'];

    /** @var TrustedAppClient $trustedAppClient */
    private $trustedAppClient;

    /**
     * @return string
     * @throws ClientException
     * Get the Stalactite API RSA public key
     */
    public function getRSAPublicKey(): string
    {
        try {
            return $this->getHttpClient()->request('GET', $this->apiHost . self::API_URL_PREFIX . '/publicKey')->getContent();
        } catch (Throwable $t) {
            throw new ClientException('Error while fetching Stalactite API RSA public key', ClientException::CLIENT_TRANSPORT_ERROR);
        }
    }

    /**
     * @param string $jwt
     * @return bool
     * @throws ClientException
     * Check if the given JWT is a valid Stalactite API JWT
     */
    public function validate(string $jwt): bool
    {
        try {
            $token = $this->getTokenFromString($jwt);
        } catch (Throwable $t) {
            throw new ClientException('Invalid user JWT', ClientException::INVALID_USER_JWT_ERROR, $t);
        }

        $data = new ValidationData();
        $data->setIssuer(self::JWT_ISSUER);
        $data->has('iss');
        $data->has('aud');
        $data->has('type');
        $data->has('sub');
        $data->has('iat');
        $data->has('exp');

        if ($token->validate($data) && !$token->isExpired() && in_array($token->getClaim('type'), self::AUTHORIZED_JWT_TYPES, true)) {

            $signer = new Sha256();
            $publicKey = new Key($this->getRSAPublicKey());

            try {
                return $token->verify($signer, $publicKey);
            } catch (InvalidArgumentException $e) { // thrown by the library on invalid key
                throw new ClientException('Invalid RSA public key', ClientException::INVALID_STALACTITE_RSA_PUBLIC_KEY_ERROR);
            } catch (Throwable $t) { // other exceptions result in an invalid token
                return false;
            }
        }

        return false;
    }

    /**
     * @param string $appName
     * @param string $appToken
     * @param string $userGoogleJwt
     * @return array
     * @throws ClientException
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function login(string $appName, string $appToken, string $userGoogleJwt): array
    {
        $data = [
            'appName' => $appName,
            'appToken' => $appToken,
            'userGoogleJwt' => $userGoogleJwt
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'jwt' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('POST', $this->apiHost . self::API_URL_PREFIX . '/login', ['json' => $data], $schema);
    }

    public function trustedApps(): TrustedAppClient
    {
        if (!($this->trustedAppClient instanceof TrustedAppClient)) {
            $this->trustedAppClient = new TrustedAppClient($this->apiHost, $this->userAgent);
            $this->trustedAppClient->setHttpClient($this->getHttpClient());
        }

        return $this->trustedAppClient;
    }

    /**
     * @param string $jwt
     * @return Token
     * @throws Throwable
     */
    protected function getTokenFromString(string $jwt): Token
    {
        return (new Parser())->parse($jwt);
    }
}