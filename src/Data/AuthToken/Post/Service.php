<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Post;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\AuthToken\Post
 */
class Service extends
    AbstractService
{
    /**
     * getAllPosts
     *
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getAllPosts(
        string $apiAuthToken
    ): Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );

        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/auth-token/posts'
                ))
                    ->setJwt((string)$jwt)
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
     * @param string $apiAuthToken
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
        string $apiAuthToken
    ): Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );

        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/auth-token/posts/%s'
                ))
                    ->setJwt((string)$jwt)
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
     * getUsers
     *
     * @param string $uid
     * @param string $apiAuthToken
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
        string $apiAuthToken
    ): Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );

        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/auth-token/posts/%s/users'
                ))
                    ->setJwt((string)$jwt)
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
