<?php

namespace jalismrs\Stalactite\Client\DataManagement;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Model\Post;

class PostClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/posts';

    /**
     * @param string $jwt
     * @return array
     */
    public function getAll(string $jwt): array
    {

    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return array
     */
    public function get(Post $post, string $jwt): array
    {

    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return array
     */
    public function create(Post $post, string $jwt): array
    {

    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return array
     */
    public function update(Post $post, string $jwt): array
    {

    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return array
     */
    public function delete(Post $post, string $jwt): array
    {

    }
}