<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Data\User\Post\Service as PostService;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;
use function array_merge;

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
            'create'                => [
                'endpoint'      => '/data/users',
                'method'        => 'POST',
                'validation'    => [
                    'user' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'null'   => true,
                        'schema' => Schema::USER,
                    ],
                ],
                'normalization' => [
                    AbstractNormalizer::GROUPS => [
                        'create',
                    ],
                ],
            ],
            'delete'                => [
                'endpoint' => '/data/users/%s',
                'method'   => 'DELETE',
            ],
            'getAll'                => [
                'endpoint'   => '/data/users',
                'method'     => 'GET',
                'validation' => [
                    'users' => [
                        'type'   => JsonRule::LIST_TYPE,
                        'schema' => Schema::USER,
                    ],
                ],
            ],
            'getByEmailAndGoogleId' => [
                'endpoint'   => '/data/users',
                'method'     => 'GET',
                'validation' => [
                    'user' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'null'   => true,
                        'schema' => Schema::USER,
                    ],
                ],
            ],
            'get'                   => [
                'endpoint'   => '/data/users/%s',
                'method'     => 'GET',
                'validation' => [
                    'user' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'null'   => true,
                        'schema' => Schema::USER,
                    ],
                ],
            ],
            'update'                => [
                'endpoint'      => '/data/users/%s',
                'method'        => 'PUT',
                'normalization' => [
                    AbstractNormalizer::GROUPS => [
                        'update',
                    ],
                ],
            ],
        ];
    }
    
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
        $response = $this
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
        $response = $this
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
    public function getByEmailAndGoogleId(
        string $email,
        string $googleId,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getByEmailAndGoogleId'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'query'   => [
                        'email'    => $email,
                        'googleId' => $googleId
                    ]
                ]
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
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['create'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => array_merge(
                        Serializer::getInstance()
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
                ]
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
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['update'],
                [
                    $userModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $userModel,
                ]
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
    public function deleteUser(
        string $uid,
        string $jwt
    ) : Response {
        $response = $this
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
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}
