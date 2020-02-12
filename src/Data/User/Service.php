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
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;
use Jalismrs\Stalactite\Client\RequestConfiguration;
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
            'create'                => (new RequestConfiguration(
                '/data/users'
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
                            'user' => $response['user'] === null
                                ? null
                                : ModelFactory::createUser($response['user']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'user' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'null'   => true,
                            'schema' => Schema::USER,
                        ],
                    ]
                ),
            'delete'                => (new RequestConfiguration(
                '/data/users/%s'
            ))
                ->setMethod('DELETE'),
            'getAll'                => (new RequestConfiguration(
                '/data/users'
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
            'getByEmailAndGoogleId' => (new RequestConfiguration(
                '/data/users'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'user' => $response['user'] === null
                                ? null
                                : ModelFactory::createUser($response['user']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'user' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'null'   => true,
                            'schema' => Schema::USER,
                        ],
                    ]
                ),
            'get'                   => (new RequestConfiguration(
                '/data/users/%s'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'user' => $response['user'] === null
                                ? null
                                : ModelFactory::createUser($response['user']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'user' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'null'   => true,
                            'schema' => Schema::USER,
                        ],
                    ]
                ),
            'update'                => (new RequestConfiguration(
                '/data/users/%s'
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
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    /**
     * leads
     *
     * @return Lead\Service
     *
     * @throws RequestConfigurationException
     */
    public function leads() : Lead\Service
    {
        if ($this->serviceLead === null) {
            $this->serviceLead = new Lead\Service($this->getClient());
        }
        
        return $this->serviceLead;
    }
    
    /**
     * me
     *
     * @return Me\Service
     *
     * @throws RequestConfigurationException
     */
    public function me() : Me\Service
    {
        if ($this->serviceMe === null) {
            $this->serviceMe = new Me\Service($this->getClient());
        }
        
        return $this->serviceMe;
    }
    
    /**
     * posts
     *
     * @return PostService
     *
     * @throws RequestConfigurationException
     */
    public function posts() : PostService
    {
        if ($this->servicePost === null) {
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
     * @throws SerializerException
     */
    public function getAllUsers(
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
     * getUser
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
    public function getUser(
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
     * getByEmailAndGoogleId
     *
     * @param string $email
     * @param string $googleId
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getByEmailAndGoogleId(
        string $email,
        string $googleId,
        string $jwt
    ) : Response {
        return $this
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
        return $this
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
        return $this
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
    }
    
    /**
     * deleteUser
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
    public function deleteUser(
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
}
