<?php

namespace Jalismrs\Stalactite\Client\Data\Post\Permission;

use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Permission;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;

class Service extends AbstractService
{
    /**
     * @param Post $post
     * @param array $permissions
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function addPermissions(Post $post, array $permissions, Token $jwt): ?Response
    {
        if ($post->getUid() === null) {
            throw new DataServiceException('Post lacks an uid', DataServiceException::MISSING_POST_UID);
        }

        if (count($permissions) === 0) {
            return null;
        }

        try {
            $permissions = ModelHelper::getUids($permissions, Permission::class);
        } catch (InvalidArgumentException $e) {
            $this->getLogger()->error($e);
            throw new DataServiceException('Error while getting permissions uids', DataServiceException::INVALID_MODEL, $e);
        }

        $endpoint = new Endpoint('/data/posts/%s/permissions', 'POST');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'json' => ['permissions' => $permissions],
            'uriParameters' => [$post->getUid()]
        ]);
    }

    /**
     * @param Post $post
     * @param array $permissions
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function removePermissions(Post $post, array $permissions, Token $jwt): ?Response
    {
        if ($post->getUid() === null) {
            throw new DataServiceException('Post lacks an uid', DataServiceException::MISSING_POST_UID);
        }

        if (count($permissions) === 0) {
            return null;
        }

        try {
            $permissions = ModelHelper::getUids($permissions, Permission::class);
        } catch (InvalidArgumentException $e) {
            $this->getLogger()->error($e);
            throw new DataServiceException('Error while getting permissions uids', DataServiceException::INVALID_MODEL, $e);
        }

        $endpoint = new Endpoint('/data/posts/%s/permissions', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'json' => ['permissions' => $permissions],
            'uriParameters' => [$post->getUid()]
        ]);
    }
}