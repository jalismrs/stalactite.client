<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;
use Jalismrs\Stalactite\Client\RequestConfiguration;
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
    /**
     * Service constructor.
     *
     * @param Client $client
     *
     * @throws RequestConfigurationException
     */
    public function __construct(
        Client $client
    ) {
        parent::__construct(
            $client
        );
        
        $this->requestConfigurations = [
            'create'   => (new RequestConfiguration(
                '/data/posts'
            ))
                ->setMethod('POST')
                ->setNormalization(
                    [
                        AbstractNormalizer::GROUPS => [
                            'create',
                        ],
                    ]
                )
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'post' => $response['post'] === null
                                ? null
                                : ModelFactory::createPost($response['post']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'post' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'null'   => true,
                            'schema' => Schema::POST,
                        ],
                    ]
                ),
            'delete'   => (new RequestConfiguration(
                '/data/posts/%s'
            ))
                ->setMethod('DELETE'),
            'getAll'   => (new RequestConfiguration(
                '/data/posts'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'posts' => array_map(
                                static function($post) {
                                    return ModelFactory::createPost($post);
                                },
                                $response['posts']
                            ),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'posts' => [
                            'type'   => JsonRule::LIST_TYPE,
                            'schema' => Schema::POST,
                        ],
                    ]
                ),
            'get'      => (new RequestConfiguration(
                '/data/posts/%s'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'post' => $response['post'] === null
                                ? null
                                : ModelFactory::createPost($response['post']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'post' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'null'   => true,
                            'schema' => Schema::POST,
                        ],
                    ]
                ),
            'getUsers' => (new RequestConfiguration(
                '/data/posts/%s/users'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'users' => array_map(
                                static function($user) {
                                    return ModelFactory::createUser($user);
                                },
                                $response['users']
                            ),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'users' => [
                            'type'   => JsonRule::LIST_TYPE,
                            'schema' => Schema::USER,
                        ],
                    ]
                ),
            'update'   => (new RequestConfiguration(
                '/data/posts/%s'
            ))
                ->setMethod('PUT')
                ->setNormalization(
                    [
                        AbstractNormalizer::GROUPS => [
                            'update',
                        ],
                    ]
                ),
        ];
    }
    
    /**
     * getAllPosts
     *
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getAllPosts(
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getAll'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
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
     * @throws SerializerException
     */
    public function getPost(
        string $uid,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['get'],
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
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
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['create'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $postModel,
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
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['update'],
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
    }
    
    /**
     * deletePost
     *
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function deletePost(
        string $uid,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['delete'],
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
    }
    
    /**
     * getUsers
     *
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getUsers(
        string $uid,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getUsers'],
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
    }
}
