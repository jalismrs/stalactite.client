<?php

namespace Jalismrs\Stalactite\Client\Data\Permission;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Permission;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data\Permission
 */
class Service extends
    AbstractService
{
    /**
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function all(Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/permissions');
        $endpoint->setResponseValidationSchema(
            new JsonSchema(
                Permission::getSchema(),
                JsonSchema::LIST_TYPE
            )
        )
            ->setResponseFormatter(
                static function (array $response): array {
                    return array_map(
                        static fn(array $permission): Permission => ModelFactory::createPermission($permission),
                        $response
                    );
                }
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                ]
            );
    }

    /**
     * @param string $uid
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function get(
        string $uid,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint('/data/permissions/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Permission::getSchema()))
            ->setResponseFormatter(fn(array $response): Permission => ModelFactory::createPermission($response));

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'uriParameters' => [$uid],
                ]
            );
    }

    /**
     * @param string $uid
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function exists(
        string $uid,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint(
            '/data/permissions/%s',
            'HEAD'
        );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'uriParameters' => [$uid],
                ]
            );
    }

    /**
     * @param Permission $permission
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function create(
        Permission $permission,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint(
            '/data/permissions',
            'POST'
        );
        $endpoint->setResponseValidationSchema(new JsonSchema(Permission::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): Permission => ModelFactory::createPermission($response)
            );

        $data = Normalizer::getInstance()
            ->normalize(
                $permission,
                [
                    AbstractNormalizer::GROUPS => ['create'],
                ]
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'json' => $data,
                ]
            );
    }

    /**
     * @param Permission $permission
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function update(
        Permission $permission,
        Token $jwt
    ): Response
    {
        if ($permission->getUid() === null) {
            throw new DataServiceException(
                'Permission lacks an uid',
                DataServiceException::MISSING_PERMISSION_UID
            );
        }

        $endpoint = new Endpoint(
            '/data/permissions/%s',
            'PUT'
        );

        $data = Normalizer::getInstance()
            ->normalize(
                $permission,
                [
                    AbstractNormalizer::GROUPS => ['update'],
                ]
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'json' => $data,
                    'uriParameters' => [$permission->getUid()],
                ]
            );
    }

    /**
     * @param Permission $permission
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function delete(
        Permission $permission,
        Token $jwt
    ): Response
    {
        if ($permission->getUid() === null) {
            throw new DataServiceException(
                'Permission lacks an uid',
                DataServiceException::MISSING_PERMISSION_UID
            );
        }

        $endpoint = new Endpoint(
            '/data/permissions/%s',
            'DELETE'
        );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'uriParameters' => [$permission->getUid()],
                ]
            );
    }
}
