<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\AuthToken\Domain
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
            'getAll'             => [
                'endpoint'   => '/data/auth-token/domains',
                'method'     => 'GET',
                'response'   => static function(array $response) : array {
                    return [
                        'domains' => array_map(
                            static function($domain) {
                                return ModelFactory::createDomain($domain);
                            },
                            $response['domains']
                        ),
                    ];
                },
                'validation' => [
                    'domains' => [
                        'type'   => JsonRule::LIST_TYPE,
                        'schema' => Schema::DOMAIN,
                    ],
                ],
            ],
            'getByNameAndApiKey' => [
                'endpoint'   => '/data/auth-token/domains',
                'method'     => 'GET',
                'response'   => static function(array $response) : array {
                    return [
                        'domains' => array_map(
                            static function($domain) {
                                return ModelFactory::createDomain($domain);
                            },
                            $response['domains']
                        ),
                    ];
                },
                'validation' => [
                    'domains' => [
                        'type'   => JsonRule::LIST_TYPE,
                        'schema' => Schema::DOMAIN,
                    ],
                ],
            ],
            'getByName'          => [
                'endpoint'   => '/data/auth-token/domains',
                'method'     => 'GET',
                'response'   => static function(array $response) : array {
                    return [
                        'domains' => array_map(
                            static function($domain) {
                                return ModelFactory::createDomain($domain);
                            },
                            $response['domains']
                        ),
                    ];
                },
                'validation' => [
                    'domains' => [
                        'type'   => JsonRule::LIST_TYPE,
                        'schema' => Schema::DOMAIN,
                    ],
                ],
            ],
            'get'                => [
                'endpoint'   => '/data/auth-token/domains/%s',
                'method'     => 'GET',
                'response'   => static function(array $response) : array {
                    return [
                        'domain' => null === $response['domain']
                            ? null
                            : ModelFactory::createDomain($response['domain']),
                    ];
                },
                'validation' => [
                    'domain' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'null'   => true,
                        'schema' => Schema::DOMAIN,
                    ],
                ],
            ],
        ];
    }
    
    /**
     * getAllDomains
     *
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllDomains(
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
     * @param string $name
     * @param string $apiKey
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByNameAndApiKey(
        string $name,
        string $apiKey,
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
                $this->requestConfigurations['getByNameAndApiKey'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ],
                    'query'   => [
                        'name'   => $name,
                        'apiKey' => $apiKey
                    ]
                ]
            );
    }
    
    /**
     * @param string $name
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByName(
        string $name,
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
                $this->requestConfigurations['getByName'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ],
                    'query'   => [
                        'name' => $name
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
    public function getDomain(
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
