<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\RequestConfiguration;
use Jalismrs\Stalactite\Client\Response;
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
     * Service constructor.
     *
     * @param Client $client
     */
    public function __construct(
        Client $client
    ) {
        parent::__construct(
            $client
        );
        
        $this->requestConfigurations = [
            'getAll'   => (new RequestConfiguration(
                '/data/auth-token/posts'
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
                '/data/auth-token/posts/%s'
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
                '/data/auth-token/posts/%s/users'
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
        ];
    }
    
    /**
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllPosts(
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getAll'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
    }
    
    /**
     * @param string $uid
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getPost(
        string $uid,
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['get'],
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
    }
    
    /**
     * @param string $uid
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getUsers(
        string $uid,
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getUsers'],
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
    }
}
