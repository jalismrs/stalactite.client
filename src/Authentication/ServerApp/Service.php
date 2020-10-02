<?php

namespace Jalismrs\Stalactite\Client\Authentication\ServerApp;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Authentication\Model\ServerApp;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class Service extends AbstractService
{
    /**
     * @param Token $token
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function all(Token $token): Response
    {
        $endpoint = new Endpoint('/auth/serverApps');
        $endpoint
            ->setResponseValidationSchema(new JsonSchema(ServerApp::getSchema(), JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn(array $serverApp): ServerApp => ModelFactory::createServerApp($serverApp),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$token
        ]);
    }

    /**
     * @param string $uid
     * @param Token $token
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function get(string $uid, Token $token): Response
    {
        $endpoint = new Endpoint('/auth/serverApps/%s');
        $endpoint
            ->setResponseValidationSchema(new JsonSchema(ServerApp::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): ServerApp => ModelFactory::createServerApp($response)
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$token,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param ServerApp $serverApp
     * @param Token $token
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function create(ServerApp $serverApp, Token $token): Response
    {
        $schema = new JsonSchema(ServerApp::getSchema());

        $endpoint = new Endpoint('/auth/serverApps', 'POST');
        $endpoint
            ->setResponseValidationSchema($schema)
            ->setResponseFormatter(
                static fn(array $response): ServerApp => ModelFactory::createServerApp($response)
            );

        $data = Normalizer::getInstance()->normalize($serverApp, [
            AbstractNormalizer::GROUPS => ['create']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$token,
            'json' => $data
        ]);
    }

    /**
     * @param ServerApp $serverApp
     * @param Token $token
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function update(ServerApp $serverApp, Token $token): Response
    {
        if ($serverApp->getUid() === null) {
            throw new AuthenticationServiceException('ServerApp lacks a uid', AuthenticationServiceException::MISSING_SERVER_APP_UID);
        }

        $endpoint = new Endpoint('/auth/serverApps/%s', 'PUT');

        $data = Normalizer::getInstance()->normalize($serverApp, [
            AbstractNormalizer::GROUPS => ['update']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$token,
            'json' => $data,
            'uriParameters' => [$serverApp->getUid()]
        ]);
    }

    /**
     * @param ServerApp $serverApp
     * @param Token $token
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function delete(ServerApp $serverApp, Token $token): Response
    {
        if ($serverApp->getUid() === null) {
            throw new AuthenticationServiceException('ServerApp lacks a uid', AuthenticationServiceException::MISSING_SERVER_APP_UID);
        }

        $endpoint = new Endpoint('/auth/serverApps/%s', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$token,
            'uriParameters' => [$serverApp->getUid()]
        ]);
    }

    /**
     * @param ServerApp $serverApp
     * @param Token $token
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function resetTokenSignatureKey(ServerApp $serverApp, Token $token): Response
    {
        if ($serverApp->getUid() === null) {
            throw new AuthenticationServiceException('ServerApp lacks a uid', AuthenticationServiceException::MISSING_SERVER_APP_UID);
        }

        $endpoint = new Endpoint('/auth/serverApps/%s/tokenSignatureKey/reset', 'PUT');
        $endpoint
            ->setResponseValidationSchema(new JsonSchema(ServerApp::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): ServerApp => ModelFactory::createServerApp($response)
            );

        $data = Normalizer::getInstance()->normalize($serverApp, [
            AbstractNormalizer::GROUPS => ['tokenSignatureKey']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$token,
            'json' => $data,
            'uriParameters' => [$serverApp->getUid()]
        ]);
    }
}