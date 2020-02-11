<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\Post
 */
class Service extends
    AbstractService
{
    private const REQUEST_CREATE_CONFIGURATION    = [
        'endpoint'      => '/data/posts',
        'method'        => 'POST',
        'schema'        => [
            'post' => [
                'type'   => JsonRule::OBJECT_TYPE,
                'null'   => true,
                'schema' => Schema::POST
            ]
        ],
        'normalization' => [
            AbstractNormalizer::GROUPS => [
                'create',
            ],
        ],
    ];
    private const REQUEST_DELETE_CONFIGURATION    = [
        'endpoint' => '/data/posts/%s',
        'method'   => 'DELETE',
    ];
    private const REQUEST_GET_ALL_CONFIGURATION   = [
        'endpoint' => '/data/posts',
        'method'   => 'GET',
        'schema'   => [
            'posts' => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::POST
            ]
        ],
    ];
    private const REQUEST_GET_CONFIGURATION       = [
        'endpoint' => '/data/posts/%s',
        'method'   => 'GET',
        'schema'   => [
            'post' => [
                'type'   => JsonRule::OBJECT_TYPE,
                'null'   => true,
                'schema' => Schema::POST
            ]
        ],
    ];
    private const REQUEST_GET_USERS_CONFIGURATION = [
        'endpoint' => '/data/posts/%s/users',
        'method'   => 'GET',
        'schema'   => [
            'users' => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::USER
            ]
        ],
    ];
    private const REQUEST_UPDATE_CONFIGURATION    = [
        'endpoint'      => '/data/posts/%s',
        'method'        => 'PUT',
        'normalization' => [
            AbstractNormalizer::GROUPS => [
                'update',
            ],
        ],
    ];
    
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
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_ALL_CONFIGURATION,
                [],
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
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_CONFIGURATION,
                [
                    $uid,
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
                'post' => null === $response['post']
                    ? null
                    : ModelFactory::createPost($response['post']),
            ]
        );
    }
    
    /**
     * createPost
     *
     * @param Post   $postModel
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
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_CREATE_CONFIGURATION,
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $postModel,
                ]
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
     * @param Post   $postModel
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
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_UPDATE_CONFIGURATION,
                [
                    $postModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $postModel,
                ]
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
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_DELETE_CONFIGURATION,
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
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
    public function getUsers(
        string $uid,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_USERS_CONFIGURATION,
                [
                    $uid,
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
                'users' => array_map(
                    static function($user) {
                        return ModelFactory::createUser($user);
                    },
                    $response['users']
                )
            ]
        );
    }
}
