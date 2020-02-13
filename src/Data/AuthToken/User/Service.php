<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Request;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\AuthToken\User
 */
class Service extends
    AbstractService
{
    /**
     * getAllUsers
     *
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getAllUsers(
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
                (new Request(
                    '/data/auth-token/users'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => (string)$jwt
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
     * getByEmailAndGoogleId
     *
     * @param string $email
     * @param string $googleId
     * @param string $apiAuthToken
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
                (new Request(
                    '/data/auth-token/users'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => (string)$jwt
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
     * getUser
     *
     * @param string $uid
     * @param string $apiAuthToken
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
                (new Request(
                    '/data/auth-token/users/%s'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => (string)$jwt
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
}
