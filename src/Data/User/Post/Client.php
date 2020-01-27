<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\User\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Data\User\Post
 */
class Client extends
    AbstractClient
{
    /**
     * getAllPosts
     *
     * @param User $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllPosts(
        User $userModel,
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

        $response = $this->get(
            vsprintf(
                '%s/data/users/%s/posts',
                [
                    $this->host,
                    $userModel->getUid(),
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
                'posts' => array_map(
                    static function ($post) {
                        return ModelFactory::createPostModel($post);
                    },
                    $response['posts']
                )
            ]
        );
    }

    /**
     * addPosts
     *
     * @param User $userModel
     * @param array $postModels
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addPosts(
        User $userModel,
        array $postModels,
        string $jwt
    ): Response
    {
        $body = [
            'posts' => []
        ];

        foreach ($postModels as $postModel) {
            if (!$postModel instanceof Post) {
                throw new ClientException(
                    '$posts array parameter must be a Post model array',
                    ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
                );
            }

            if (null !== $postModel->getUid()) {
                $body['posts'][] = $postModel->getUid();
            }
        }

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

        $response = $this->post(
            vsprintf(
                '%s/data/users/%s/posts',
                [
                    $this->host,
                    $userModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json' => $body,
            ],
            $schema
        );

        return (new Response(
            $response['success'],
            $response['error']
        ));
    }

    /**
     * removePosts
     *
     * @param User $userModel
     * @param array $postModels
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removePosts(
        User $userModel,
        array $postModels,
        string $jwt
    ): Response
    {
        $body = [
            'posts' => []
        ];

        foreach ($postModels as $postModel) {
            if (!$postModel instanceof Post) {
                throw new ClientException(
                    '$posts array parameter must be a Post model array',
                    ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
                );
            }

            if (null !== $postModel->getUid()) {
                $body['posts'][] = $postModel->getUid();
            }
        }

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

        $response = $this->delete(
            vsprintf(
                '%s/data/users/%s/posts',
                [
                    $this->host,
                    $userModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json' => $body,
            ],
            $schema
        );

        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}