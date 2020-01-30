<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;
use function vsprintf;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\Post
 */
class Service extends
    AbstractService
{
    /**
     * getAll
     *
     * @param string $jwt
     *
     * @return Response
     *
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function getAllPosts(
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'posts' => [
                    'type' => JsonRule::LIST_TYPE,
                    'schema' => Schema::POST
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->get(
            '/data/posts',
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'posts' => array_map(
                    static function ($post) {
                        return ModelFactory::createPost($post);
                    },
                    $response['posts']
                )
            ]
        );
    }

    /**
     * getPost
     *
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getPost(
        string $uid,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'post' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::POST
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->get(
            vsprintf(
                '/data/posts/%s',
                [
                    $uid,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'post' => null === $response['post']
                    ? null
                    : ModelFactory::createPost($response['post']),
            ]
        );
    }

    /**
     * createPost
     *
     * @param Post $postModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function createPost(
        Post $postModel,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'post' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::POST
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->post(
            '/data/posts',
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json' => Serializer::getInstance()->normalize(
                    $postModel,
                    [
                        AbstractNormalizer::GROUPS => [
                            'create',
                        ],
                    ]
                ),
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'post' => null === $response['post']
                    ? null
                    : ModelFactory::createPost($response['post']),
            ]
        );
    }

    /**
     * updatePost
     *
     * @param Post $postModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function updatePost(
        Post $postModel,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->put(
            vsprintf(
                '/data/posts/%s',
                [
                    $postModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json' => Serializer::getInstance()->normalize(
                    $postModel,
                    [
                        AbstractNormalizer::GROUPS => [
                            'update',
                        ],
                    ]
                ),
            ],
            $schema
        );

        return (new Response(
            $response['success'],
            $response['error']
        ));
    }

    /**
     * delete
     *
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     *
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function deletePost(
        string $uid,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->delete(
            vsprintf(
                '/data/posts/%s',
                [
                    $uid,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );

        return (new Response(
            $response['success'],
            $response['error']
        ));
    }

    /**
     * getUsers
     *
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     *
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function getUsers(string $uid, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'users' => [
                    'type' => JsonRule::LIST_TYPE,
                    'schema' => Schema::USER
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->get(
            vsprintf(
                '/data/posts/%s/users',
                [
                    $uid,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'users' => array_map(
                    static function ($user) {
                        return ModelFactory::createUser($user);
                    },
                    $response['users']
                )
            ]
        );
    }
}
