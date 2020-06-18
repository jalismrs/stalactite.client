<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication\TrustedApp;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Authentication\TrustedApp
 */
class Service extends AbstractService
{
    /**
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function all(Token $jwt): Response
    {
        $endpoint = new Endpoint('/auth/trustedApps');
        $endpoint->setResponseValidationSchema(new JsonSchema(TrustedApp::getSchema(), JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn(array $trustedApp): TrustedApp => ModelFactory::createTrustedApp($trustedApp),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt
        ]);
    }

    /**
     * @param string $uid
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function get(string $uid, Token $jwt): Response
    {
        $endpoint = new Endpoint('/auth/trustedApps/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(TrustedApp::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): TrustedApp => ModelFactory::createTrustedApp($response)
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     */
    public function create(TrustedApp $trustedApp, Token $jwt): Response
    {
        $schema = new JsonSchema(array_merge(
            TrustedApp::getSchema(),
            ['resetToken' => ['type' => JsonRule::STRING_TYPE]]
        ));

        $endpoint = new Endpoint('/auth/trustedApps', 'POST');
        $endpoint->setResponseValidationSchema($schema)
            ->setResponseFormatter(
                static fn(array $response): TrustedApp => ModelFactory::createTrustedApp($response)
            );

        $data = Normalizer::getInstance()->normalize($trustedApp, [
            AbstractNormalizer::GROUPS => ['create']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'json' => $data
        ]);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     */
    public function update(TrustedApp $trustedApp, Token $jwt): Response
    {
        if ($trustedApp->getUid() === null) {
            throw new AuthenticationServiceException('TrustedApp lacks a uid', AuthenticationServiceException::MISSING_TRUSTED_APP_UID);
        }

        $endpoint = new Endpoint('/auth/trustedApps/%s', 'PUT');

        $data = Normalizer::getInstance()->normalize($trustedApp, [
            AbstractNormalizer::GROUPS => ['update']
        ]);

        return $this
            ->getClient()
            ->request($endpoint, [
                'jwt' => (string)$jwt,
                'json' => $data,
                'uriParameters' => [$trustedApp->getUid()]
            ]);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     */
    public function delete(TrustedApp $trustedApp, Token $jwt): Response
    {
        if ($trustedApp->getUid() === null) {
            throw new AuthenticationServiceException('TrustedApp lacks a uid', AuthenticationServiceException::MISSING_TRUSTED_APP_UID);
        }

        $endpoint = new Endpoint('/auth/trustedApps/%s', 'DELETE');
        $data = Normalizer::getInstance()->normalize($trustedApp, [
            AbstractNormalizer::GROUPS => ['delete']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'json' => $data,
            'uriParameters' => [$trustedApp->getUid()]
        ]);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     */
    public function resetAuthToken(TrustedApp $trustedApp, Token $jwt): Response
    {
        if ($trustedApp->getUid() === null) {
            throw new AuthenticationServiceException('TrustedApp lacks a uid', AuthenticationServiceException::MISSING_TRUSTED_APP_UID);
        }

        $endpoint = new Endpoint('/auth/trustedApps/%s/authToken/reset', 'PUT');
        $endpoint->setResponseValidationSchema(new JsonSchema(TrustedApp::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): TrustedApp => ModelFactory::createTrustedApp($response)
            );

        $data = Normalizer::getInstance()->normalize($trustedApp, [
            AbstractNormalizer::GROUPS => ['reset']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'json' => $data,
            'uriParamaters' => [$trustedApp->getUid()]
        ]);
    }
}
