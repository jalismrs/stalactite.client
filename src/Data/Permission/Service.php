<?php

namespace Jalismrs\Stalactite\Client\Data\Permission;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Permission;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class Service extends AbstractService
{
    /**
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getAllPermissions(Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/permissions');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::PERMISSION, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(static fn(array $permission): Permission => ModelFactory::createPermission($permission), $response);
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
    public function getPermission(string $uid, Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/permissions/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::PERMISSION))
            ->setResponseFormatter(fn(array $response): Permission => ModelFactory::createPermission($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param Permission $permission
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     */
    public function createPermission(Permission $permission, Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/permissions', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::PERMISSION))
            ->setResponseFormatter(static fn(array $response): Permission => ModelFactory::createPermission($response));

        $data = Normalizer::getInstance()->normalize($permission, [
            AbstractNormalizer::GROUPS => ['create']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'json' => $data
        ]);
    }

    /**
     * @param Permission $permission
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     */
    public function updatePermission(Permission $permission, Token $jwt): Response
    {
        if ($permission->getUid() === null) {
            throw new DataServiceException('Permission lacks an uid', DataServiceException::MISSING_PERMISSION_UID);
        }

        $endpoint = new Endpoint('/data/permissions/%s', 'PUT');

        $data = Normalizer::getInstance()->normalize($permission, [
            AbstractNormalizer::GROUPS => ['update']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'json' => $data,
            'uriParameters' => [$permission->getUid()]
        ]);
    }

    /**
     * @param Permission $permission
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function deletePermission(Permission $permission, Token $jwt): Response
    {
        if ($permission->getUid() === null) {
            throw new DataServiceException('Permission lacks an uid', DataServiceException::MISSING_PERMISSION_UID);
        }

        $endpoint = new Endpoint('/data/permissions/%s', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$permission->getUid()]
        ]);
    }
}