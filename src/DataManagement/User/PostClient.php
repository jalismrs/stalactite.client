<?php

namespace jalismrs\Stalactite\Client\DataManagement\User;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Model\Post;
use jalismrs\Stalactite\Client\DataManagement\Model\User;

class PostClient extends AbstractClient
{
    public const API_URL_PREFIX = '/posts';

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     */
    public function getAll(User $user, string $jwt): array
    {

    }

    /**
     * @param User $user
     * @param array|Post[] $posts
     * @param string $jwt
     * @return array
     */
    public function addPosts(User $user, array $posts, string $jwt): array
    {

    }

    /**
     * @param User $user
     * @param array|Post[] $posts
     * @param string $jwt
     * @return array
     */
    public function deletePosts(User $user, array $posts, string $jwt): array
    {

    }
}