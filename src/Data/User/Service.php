<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User;

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
use Jalismrs\Stalactite\Client\Data\User\Post\Service as PostService;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;
use function array_merge;
use function vsprintf;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\User
 */
class Service extends
    AbstractService
{
    private $serviceLead;
    private $serviceMe;
    private $servicePost;
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    /**
     * lead
     *
     * @return Lead\Service
     */
    public function leads() : Lead\Service
    {
        if (null === $this->serviceLead) {
            $this->serviceLead = new Lead\Service($this->getClient());
        }
        
        return $this->serviceLead;
    }
    
    /**
     * me
     *
     * @return Me\Service
     */
    public function me() : Me\Service
    {
        if (null === $this->serviceMe) {
            $this->serviceMe = new Me\Service($this->getClient());
        }
        
        return $this->serviceMe;
    }
    
    /**
     * posts
     *
     * @return PostService
     */
    public function posts() : PostService
    {
        if (null === $this->servicePost) {
            $this->servicePost = new PostService($this->getClient());
        }
        
        return $this->servicePost;
    }
    
    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    /**
     * getAllUsers
     *
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllUsers(
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'users'   => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::USER
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->get(
                '/data/users',
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
                    static function($user) {
                        return ModelFactory::createUser($user);
                    },
                    $response['users']
                )
            ]
        );
    }
    
    /**
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getUser(
        string $uid,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'user'    => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::USER
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->get(
                vsprintf(
                    '/data/users/%s',
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
                'user' => null === $response['user']
                    ? null
                    : ModelFactory::createUser($response['user']),
            ]
        );
    }
    
    /**
     * @param string $email
     * @param string $googleId
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByEmailAndGoogleId(string $email, string $googleId, string $jwt) : Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'user'    => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::USER
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->get(
                '/data/users',
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'query'   => [
                        'email'    => $email,
                        'googleId' => $googleId
                    ]
                ],
                $schema
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'user' => null === $response['user']
                    ? null
                    : ModelFactory::createUser($response['user']),
            ]
        );
    }
    
    /**
     * @param User   $userModel
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function createUser(
        User $userModel,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'user'    => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::USER
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->post(
                '/data/users',
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => array_merge(
                        $this
                            ->getClient()
                            ->getSerializer()
                            ->normalize(
                                $userModel,
                                [
                                    AbstractNormalizer::GROUPS => [
                                        'create',
                                    ],
                                ]
                            ),
                        [
                            'leads' => ModelHelper::getUids(
                                $userModel->getLeads(),
                                Post::class
                            ),
                            'posts' => ModelHelper::getUids(
                                $userModel->getPosts(),
                                Post::class
                            ),
                        ],
                    ),
                ],
                $schema
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'user' => null === $response['user']
                    ? null
                    : ModelFactory::createUser($response['user']),
            ]
        );
    }
    
    /**
     * updateUser
     *
     * @param User   $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function updateUser(
        User $userModel,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->put(
                vsprintf(
                    '/data/users/%s',
                    [
                        $userModel->getUid(),
                    ],
                ),
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $this
                        ->getClient()
                        ->getSerializer()
                        ->normalize(
                            $userModel,
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
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function deleteUser(string $uid, string $jwt) : Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->delete(
                vsprintf(
                    '/data/users/%s',
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
}
