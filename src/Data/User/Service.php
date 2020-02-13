<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Data\User\Post\Service as PostService;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Serializer;
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
     * @throws RequestException
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
     * @throws RequestException
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
     * @throws RequestException
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
                (new Request(
                    '/data/users'
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
                (new Request(
                    '/data/users/%s'
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
                                'user' => $response['user'] === null
                                    ? null
                                    : ModelFactory::createUser($response['user']),
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
                            'user' => [
                                'type'   => JsonRule::OBJECT_TYPE,
                                'null'   => true,
                                'schema' => Schema::USER,
                            ],
                        ]
                    )
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
                (new Request(
                    '/data/users'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'query'   => [
                                'email'    => $email,
                                'googleId' => $googleId
                            ]
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
                    )
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
                (new Request(
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
                    ->setOptions(
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
                    )
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
                (new Request(
                    '/data/users/%s'
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
                            'json'    => $userModel,
                        ]
                    )
                    ->setUriDatas(
                        [
                            $userModel->getUid(),
                        ]
                    )
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
                (new Request(
                    '/data/users/%s'
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
}
