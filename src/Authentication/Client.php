<?php

namespace jalismrs\Stalactite\Client\Authentication;

use jalismrs\Stalactite\Client\AbstractClient;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use RuntimeException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class Client extends AbstractClient
{
    public const JWT_ISSUER = 'stalactite.auth-api';

    private const API_URL_PREFIX = '/auth';
    private const AUTHORIZED_JWT_TYPES = ['user', 'customer'];

    /**
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function getRSAPublicKey(): ResponseInterface
    {
        return $this->httpClient->request('GET', $this->apiHost . self::API_URL_PREFIX . '/publicKey');
    }

    /**
     * @param Token $jwt
     * @return bool if $jwt is a valid JWT from the Stalactite API
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
}