<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use function array_map;
use function vsprintf;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\User\Post
 */
class Service extends
    AbstractService
{
    private const REQUEST_ADD_POSTS_CONFIGURATION    = [
        'endpoint' => '/data/users/%s/posts',
        'method'   => 'POST',
    ];
    private const REQUEST_GET_ALL_CONFIGURATION      = [
        'endpoint' => '/data/users/%s/posts',
        'method'   => 'GET',
        'schema'   => [
            'posts'   => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::POST
            ]
        ],
    ];
    private const REQUEST_REMOVE_POSTS_CONFIGURATION = [
        'endpoint' => '/data/users/%s/posts',
        'method'   => 'DELETE',
    ];
    
    /**
     * getAllPosts
     *
     * @param User   $userModel
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
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_ALL_CONFIGURATION,
                [
                    $userModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'posts' => array_map(
                    static function($post) {
                        return ModelFactory::createPost($post);
                    },
                    $response['posts']
                )
            ]
        );
    }
    
    /**
     * addPosts
     *
     * @param User   $userModel
     * @param array  $postModels
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws InvalidArgumentException
     */
    public function addPosts(
        User $userModel,
        array $postModels,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_ADD_POSTS_CONFIGURATION,
                [
                    $userModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => [
                        'posts' => ModelHelper::getUids(
                            $postModels,
                            Post::class
                        )
                    ],
                ]
            );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
    
    /**
     * removePosts
     *
     * @param User   $userModel
     * @param array  $postModels
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws InvalidArgumentException
     */
    public function removePosts(
        User $userModel,
        array $postModels,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_REMOVE_POSTS_CONFIGURATION,
                [
                    $userModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => [
                        'posts' => ModelHelper::getUids(
                            $postModels,
                            Post::class
                        )
                    ],
                ]
            );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}
