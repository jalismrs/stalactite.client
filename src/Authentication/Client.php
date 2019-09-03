<?php

namespace jalismrs\Stalactite\Client\Authentication;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use RuntimeException;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class Client extends AbstractClient
{
    public const JWT_ISSUER = 'stalactite.auth-api';

    private const API_URL_PREFIX = '/auth';
    private const AUTHORIZED_JWT_TYPES = ['user', 'customer'];

    /**
     * @return ResponseInterface
     * @throws ClientException
     * Get the Stalactite API RSA public key
     */
    public function getRSAPublicKey(): ResponseInterface
    {
        try {
            return $this->getHttpClient()->request('GET', $this->apiHost . self::API_URL_PREFIX . '/publicKey');
        } catch (Throwable $t) {
            throw new ClientException('Error while contacting Stalactite API', ClientException::CLIENT_TRANSPORT_ERROR);
        }
    }

    /**
     * @param Token $jwt
     * @return bool
     * Check if the given JWT is a valid Stalactite API JWT
     */
    public function validate(Token $jwt): bool
    {
        $data = new ValidationData();
        $data->setIssuer(self::JWT_ISSUER);
        $data->has('iss');
        $data->has('aud');
        $data->has('type');
        $data->has('sub');
        $data->has('iat');
        $data->has('exp');

        if ($jwt->validate($data) && !$jwt->isExpired() && in_array($jwt->getClaim('type'), self::AUTHORIZED_JWT_TYPES, true)) {

            try {
                $publicKey = new Key($this->getRSAPublicKey()->getContent());
            } catch (Throwable $e) {
                throw new RuntimeException('Invalid RSA public key');
            }

            $signer = new Sha256();
            return $jwt->verify($signer, $publicKey);
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
     * Authenticate to the Stalactite API using a dedicated trusted app and the user google jwt
     */
    public function login(string $appName, string $appToken, string $userGoogleJwt): array
    {
        try {
            $response = $this->getHttpClient()->request('POST', $this->apiHost . self::API_URL_PREFIX . '/login', [
                'json' => [
                    'appName' => $appName,
                    'appToken' => $appToken,
                    'userGoogleJwt' => $userGoogleJwt
                ]
            ]);
        } catch (Throwable $t) {
            throw new ClientException('Error while contacting Stalactite API', ClientException::CLIENT_TRANSPORT_ERROR);
        }

        $schema = (new JsonSchema())->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'jwt' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        $data = new JsonData();
        try {
            $data->setData($response->getContent());
        } catch (Throwable $t) {
            throw new ClientException('Invalid json response from Stalactite API', ClientException::INVALID_API_RESPONSE_ERROR);
        }

        if (!$schema->validate($data)) {
            throw new ClientException('Invalid response from Stalactite API: ' . $schema->getLastError(), ClientException::INVALID_API_RESPONSE_ERROR);
        }

        return $data->getData();
    }
}