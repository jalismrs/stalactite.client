<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Post;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Class Service
 * @package Jalismrs\Stalactite\Client\Data\AuthToken\Post
 */
class Service extends AbstractService
{
    /**
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getAllPosts(string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/posts');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::POST, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn($post): Post => ModelFactory::createPost($post),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
        ]);
    }

    /**
     * @param string $uid
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getPost(string $uid, string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/posts/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::POST))
            ->setResponseFormatter(static fn(array $response): Post => ModelFactory::createPost($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param Post $post
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getUsers(Post $post, string $apiAuthToken): Response
    {
        if ($post->getUid() === null) {
            throw new DataServiceException('Post lacks a uid', DataServiceException::MISSING_POST_UID);
        }

        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/posts/%s/users');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::USER, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn($user): User => ModelFactory::createUser($user),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$post->getUid()]
        ]);
    }
}
