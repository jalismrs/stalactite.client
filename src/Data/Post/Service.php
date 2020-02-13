<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Request;
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
                (new Request(
                    '/data/posts'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ]
                        ]
                    )
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
                    )
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
                (new Request(
                    '/data/posts/%s'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ]
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
                    ->setUriDatas(
                        [
                            $uid,
                        ]
                    )
                    ->setValidation(
                        [
                            'post' => [
                                'type'   => JsonRule::OBJECT_TYPE,
                                'null'   => true,
                                'schema' => Schema::POST,
                            ],
                        ]
                    )
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
                (new Request(
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
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'json'    => $postModel,
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
                    )
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
                (new Request(
                    '/data/posts/%s'
                ))
                    ->setMethod('PUT')
                    ->setNormalization(
                        [
                            AbstractNormalizer::GROUPS => [
                                'update',
                            ],
                        ]
                    )
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'json'    => $postModel,
                        ]
                    )
                    ->setUriDatas(
                        [
                            $postModel->getUid(),
                        ]
                    )
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
                (new Request(
                    '/data/posts/%s'
                ))
                    ->setMethod('DELETE')
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ]
                        ]
                    )
                    ->setUriDatas(
                        [
                            $uid,
                        ]
                    )
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
                (new Request(
                    '/data/posts/%s/users'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ]
                        ]
                    )
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
                    ->setUriDatas(
                        [
                            $uid,
                        ]
                    )
                    ->setValidation(
                        [
                            'users' => [
                                'type'   => JsonRule::LIST_TYPE,
                                'schema' => Schema::USER,
                            ],
                        ]
                    )
            );
    }
}
