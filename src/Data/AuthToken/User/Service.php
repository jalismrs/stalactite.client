<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\User;

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
 * @package Jalismrs\Stalactite\Service\Data\AuthToken\User
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
            'getAll'                => (new RequestConfiguration(
                '/data/auth-token/users'
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
                '/data/auth-token/users'
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
                '/data/auth-token/users/%s'
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
     * @param string $email
     * @param string $googleId
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
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
                $this->requestConfigurations['getByEmailAndGoogleId'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ],
                    'query'   => [
                        'email'    => $email,
                        'googleId' => $googleId
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
}
