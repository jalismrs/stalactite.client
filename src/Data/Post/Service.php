<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Post;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;

/**
 * Class Service
 * @package Jalismrs\Stalactite\Client\Data\Post
 */
class Service extends AbstractService
{
    /**
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getAllPosts(string $jwt): Response
    {
        $endpoint = new Endpoint('/data/posts');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::POST, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(
                static function (array $response): array {
                    return array_map(
                        static function (array $post): Post {
                            return ModelFactory::createPost($post);
                        },
                        $response
                    );
                }
            );

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
    public function getPost(string $uid, string $jwt): Response
    {
        $endpoint = new Endpoint('/data/posts/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::POST))
            ->setResponseFormatter(
                static function (array $response): Post {
                    return ModelFactory::createPost($response);
                }
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function createPost(Post $post, string $jwt): Response
    {
        $endpoint = new Endpoint('/data/posts', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::POST))
            ->setResponseFormatter(
                static function (array $response): Post {
                    return ModelFactory::createPost($response);
                }
            );

        $data = Serializer::getInstance()->normalize($post, [
            AbstractNormalizer::GROUPS => ['create']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => $data
        ]);
    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function updatePost(Post $post, string $jwt): Response
    {
        if ($post->getUid() === null) {
            throw new DataServiceException('Post lacks an uid', DataServiceException::MISSING_POST_UID);
        }

        $endpoint = new Endpoint('/data/posts/%s', 'PUT');

        $data = Serializer::getInstance()->normalize($post, [
            AbstractNormalizer::GROUPS => ['update']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => $data,
            'uriParameters' => [$post->getUid()]
        ]);
    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function deletePost(Post $post, string $jwt): Response
    {
        if ($post->getUid() === null) {
            throw new DataServiceException('Post lacks an uid', DataServiceException::MISSING_POST_UID);
        }

        $endpoint = new Endpoint('/data/posts/%s', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$post->getUid()]
        ]);
    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getUsers(Post $post, string $jwt): Response
    {
        if ($post->getUid() === null) {
            throw new DataServiceException('Post lacks an uid', DataServiceException::MISSING_POST_UID);
        }

        $endpoint = new Endpoint('/data/posts/%s/users');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::USER, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(
                static function (array $response): array {
                    return array_map(
                        static function (array $user): User {
                            return ModelFactory::createUser($user);
                        },
                        $response
                    );
                }
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$post->getUid()]
        ]);
    }
}
