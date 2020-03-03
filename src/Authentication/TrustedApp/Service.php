<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication\TrustedApp;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Serializer;
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
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getAllTrustedApps(string $jwt): Response
    {
        $endpoint = new Endpoint('/auth/trustedApps');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::TRUSTED_APP, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(static function (array $trustedApp): TrustedApp {
                    return ModelFactory::createTrustedApp($trustedApp);
                }, $response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt
        ]);
    }

    /**
     * @param string $uid
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getTrustedApp(string $uid, string $jwt): Response
    {
        $endpoint = new Endpoint('/auth/trustedApps/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::TRUSTED_APP))
            ->setResponseFormatter(static function (array $response): TrustedApp {
                return ModelFactory::createTrustedApp($response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function createTrustedApp(TrustedApp $trustedApp, string $jwt): Response
    {
        $schema = new JsonSchema(array_merge(
            Schema::TRUSTED_APP,
            ['resetToken' => ['type' => JsonRule::STRING_TYPE]]
        ));

        $endpoint = new Endpoint('/auth/trustedApps', 'POST');
        $endpoint->setResponseValidationSchema($schema)
            ->setResponseFormatter(static function (array $response): TrustedApp {
                return ModelFactory::createTrustedApp($response);
            });

        $data = Serializer::getInstance()->normalize($trustedApp, [
            AbstractNormalizer::GROUPS => ['create']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => $data
        ]);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function updateTrustedApp(TrustedApp $trustedApp, string $jwt): Response
    {
        if ($trustedApp->getUid() === null) {
            throw new AuthenticationServiceException('TrustedApp lacks a uid', AuthenticationServiceException::MISSING_TRUSTED_APP_UID);
        }

        $endpoint = new Endpoint('/auth/trustedApps/%s', 'PUT');

        $data = Serializer::getInstance()->normalize($trustedApp, [
            AbstractNormalizer::GROUPS => ['update']
        ]);

        return $this
            ->getClient()
            ->request($endpoint, [
                'jwt' => $jwt,
                'json' => $data,
                'uriParameters' => [$trustedApp->getUid()]
            ]);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function deleteTrustedApp(TrustedApp $trustedApp, string $jwt): Response
    {
        if ($trustedApp->getUid() === null) {
            throw new AuthenticationServiceException('TrustedApp lacks a uid', AuthenticationServiceException::MISSING_TRUSTED_APP_UID);
        }

        $endpoint = new Endpoint('/auth/trustedApps/%s', 'DELETE');
        $data = Serializer::getInstance()->normalize($trustedApp, [
            AbstractNormalizer::GROUPS => ['delete']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => $data,
            'uriParameters' => [$trustedApp->getUid()]
        ]);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function resetAuthToken(TrustedApp $trustedApp, string $jwt): Response
    {
        if ($trustedApp->getUid() === null) {
            throw new AuthenticationServiceException('TrustedApp lacks a uid', AuthenticationServiceException::MISSING_TRUSTED_APP_UID);
        }

        $endpoint = new Endpoint('/auth/trustedApps/%s/authToken/reset', 'PUT');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::TRUSTED_APP))
            ->setResponseFormatter(static function (array $response): TrustedApp {
                return ModelFactory::createTrustedApp($response);
            });

        $data = Serializer::getInstance()->normalize($trustedApp, [
            AbstractNormalizer::GROUPS => ['reset']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => $data,
            'uriParamaters' => [$trustedApp->getUid()]
        ]);
    }
}
