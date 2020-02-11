<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\AuthToken\Post
 */
class Service extends
    AbstractService
{
    private const REQUEST_GET_ALL_CONFIGURATION   = [
        'endpoint' => '/data/auth-token/posts',
        'method'   => 'GET',
        'schema'   => [
            'posts'   => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::POST
            ]
        ],
    ];
    private const REQUEST_GET_CONFIGURATION       = [
        'endpoint' => '/data/auth-token/posts/%s',
        'method'   => 'GET',
        'schema'   => [
            'post'    => [
                'type'   => JsonRule::OBJECT_TYPE,
                'null'   => true,
                'schema' => Schema::POST
            ]
        ],
    ];
    private const REQUEST_GET_USERS_CONFIGURATION = [
        'endpoint' => '/data/auth-token/posts/%s/users',
        'method'   => 'GET',
        'schema'   => [
            'users'   => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::USER
            ]
        ],
    ];
    
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
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_ALL_CONFIGURATION,
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
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
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_CONFIGURATION,
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
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
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_USERS_CONFIGURATION,
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
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
