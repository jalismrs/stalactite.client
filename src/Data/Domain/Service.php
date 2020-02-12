<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\RequestConfiguration;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\DOmain
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
            'create'             => (new RequestConfiguration(
                '/data/domains'
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
                            'domain' => $response['domain'] === null
                                ? null
                                : ModelFactory::createDomain($response['domain']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'domain' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'null'   => true,
                            'schema' => Schema::DOMAIN,
                        ],
                    ]
                ),
            'delete'             => (new RequestConfiguration(
                '/data/domains/%s'
            ))
                ->setMethod('DELETE'),
            'getAll'             => (new RequestConfiguration(
                '/data/domains'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'domains' => array_map(
                                static function($domain) {
                                    return ModelFactory::createDomain($domain);
                                },
                                $response['domains']
                            ),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'domains' => [
                            'type'   => JsonRule::LIST_TYPE,
                            'schema' => Schema::DOMAIN,
                        ],
                    ]
                ),
            'getByNameAndApiKey' => (new RequestConfiguration(
                '/data/domains'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'domains' => array_map(
                                static function($domain) {
                                    return ModelFactory::createDomain($domain);
                                },
                                $response['domains']
                            ),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'domains' => [
                            'type'   => JsonRule::LIST_TYPE,
                            'schema' => Schema::DOMAIN,
                        ],
                    ]
                ),
            'getByName'          => (new RequestConfiguration(
                '/data/domains'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'domains' => array_map(
                                static function($domain) {
                                    return ModelFactory::createDomain($domain);
                                },
                                $response['domains']
                            ),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'domains' => [
                            'type'   => JsonRule::LIST_TYPE,
                            'schema' => Schema::DOMAIN,
                        ],
                    ]
                ),
            'get'                => (new RequestConfiguration(
                '/data/domains/%s'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'domain' => $response['domain'] === null
                                ? null
                                : ModelFactory::createDomain($response['domain']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'domain' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'null'   => true,
                            'schema' => Schema::DOMAIN,
                        ],
                    ]
                ),
            'update'             => (new RequestConfiguration(
                '/data/domains/%s'
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
    
    /**
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllDomains(
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
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getDomain(
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
     * @param string $name
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByName(
        string $name,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getByName'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'query'   => [
                        'name' => $name
                    ]
                ]
            );
    }
    
    /**
     * @param string $name
     * @param string $apiKey
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByNameAndApiKey(
        string $name,
        string $apiKey,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getByNameAndApiKey'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'query'   => [
                        'name'   => $name,
                        'apiKey' => $apiKey
                    ]
                ]
            );
    }
    
    /**
     * createDomain
     *
     * @param Domain $domainModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function createDomain(
        Domain $domainModel,
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
                    'json'    => $domainModel,
                ]
            );
    }
    
    /**
     * updateDomain
     *
     * @param Domain $domainModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function updateDomain(
        Domain $domainModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['update'],
                [
                    $domainModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $domainModel,
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
    public function deleteDomain(
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
