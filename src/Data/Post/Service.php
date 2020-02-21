<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Post;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getAllPosts(
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/posts'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function (array $response): array {
                            return [
                                'posts' => array_map(
                                    static function ($post) {
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
                                'type' => JsonRule::LIST_TYPE,
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getPost(
        string $uid,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/posts/%s'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function (array $response): array {
                            return [
                                'post' => $response['post'] === null
                                    ? null
                                    : ModelFactory::createPost($response['post']),
                            ];
                        }
                    )
                    ->setUriParameters(
                        [
                            $uid,
                        ]
                    )
                    ->setValidation(
                        [
                            'post' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function createPost(
        Post $postModel,
        string $jwt
    ): Response
    {
        if ($postModel->getUid() !== null) {
            throw new ServiceException(
                'Post has a uid'
            );
        }
    
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/posts',
                    'POST'
                ))
                    ->setJson($postModel)
                    ->setJwt($jwt)
                    ->setNormalization(
                        [
                            AbstractNormalizer::GROUPS => [
                                'create',
                            ],
                        ]
                    )
                    ->setResponse(
                        static function (array $response): array {
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
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function updatePost(
        Post $postModel,
        string $jwt
    ): Response
    {
        if ($postModel->getUid() === null) {
            throw new ServiceException(
                'Post lacks a uid'
            );
        }
    
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/posts/%s',
                    'PUT'
                ))
                    ->setJson($postModel)
                    ->setJwt($jwt)
                    ->setNormalization(
                        [
                            AbstractNormalizer::GROUPS => [
                                'update',
                            ],
                        ]
                    )
                    ->setUriParameters(
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function deletePost(
        string $uid,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/posts/%s',
                    'DELETE'
                ))
                    ->setJwt($jwt)
                    ->setUriParameters(
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getUsers(
        string $uid,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/posts/%s/users'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function (array $response): array {
                            return [
                                'users' => array_map(
                                    static function ($user) {
                                        return ModelFactory::createUser($user);
                                    },
                                    $response['users']
                                ),
                            ];
                        }
                    )
                    ->setUriParameters(
                        [
                            $uid,
                        ]
                    )
                    ->setValidation(
                        [
                            'users' => [
                                'type' => JsonRule::LIST_TYPE,
                                'schema' => Schema::USER,
                            ],
                        ]
                    )
            );
    }
}
