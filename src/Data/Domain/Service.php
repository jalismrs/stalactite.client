<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
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
    private const REQUEST_CREATE_CONFIGURATION                  = [
        'endpoint'      => '/data/domains',
        'method'        => 'POST',
        'schema'        => [
            'domain' => [
                'type'   => JsonRule::OBJECT_TYPE,
                'null'   => true,
                'schema' => Schema::DOMAIN
            ]
        ],
        'normalization' => [
            AbstractNormalizer::GROUPS => [
                'create',
            ],
        ],
    ];
    private const REQUEST_DELETE_CONFIGURATION                  = [
        'endpoint' => '/data/domains/%s',
        'method'   => 'DELETE',
    ];
    private const REQUEST_GET_ALL_CONFIGURATION                 = [
        'endpoint' => '/data/domains',
        'method'   => 'GET',
        'schema'   => [
            'domains' => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::DOMAIN
            ]
        ],
    ];
    private const REQUEST_GET_BY_NAME_AND_API_KEY_CONFIGURATION = [
        'endpoint' => '/data/domains',
        'method'   => 'GET',
        'schema'   => [
            'domains' => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::DOMAIN
            ]
        ],
    ];
    private const REQUEST_GET_BY_NAME_CONFIGURATION             = [
        'endpoint' => '/data/domains',
        'method'   => 'GET',
        'schema'   => [
            'domains' => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::DOMAIN
            ]
        ],
    ];
    private const REQUEST_GET_CONFIGURATION                     = [
        'endpoint' => '/data/domains/%s',
        'method'   => 'GET',
        'schema'   => [
            'domain' => [
                'type'   => JsonRule::OBJECT_TYPE,
                'null'   => true,
                'schema' => Schema::DOMAIN
            ]
        ],
    ];
    private const REQUEST_UPDATE_CONFIGURATION                  = [
        'endpoint'      => '/data/domains/%s',
        'method'        => 'PUT',
        'normalization' => [
            AbstractNormalizer::GROUPS => [
                'update',
            ],
        ],
    ];
    
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
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_ALL_CONFIGURATION,
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
                'domains' => array_map(
                    static function($domain) {
                        return ModelFactory::createDomain($domain);
                    },
                    $response['domains']
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
    public function getDomain(
        string $uid,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_CONFIGURATION,
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
                'domain' => null === $response['domain']
                    ? null
                    : ModelFactory::createDomain($response['domain']),
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
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_BY_NAME_CONFIGURATION,
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
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'domains' => array_map(
                    static function($domain) {
                        return ModelFactory::createDomain($domain);
                    },
                    $response['domains']
                )
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
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_BY_NAME_AND_API_KEY_CONFIGURATION,
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
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'domains' => array_map(
                    static function($domain) {
                        return ModelFactory::createDomain($domain);
                    },
                    $response['domains']
                )
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
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_CREATE_CONFIGURATION,
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $domainModel,
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'domain' => null === $response['domain']
                    ? null
                    : ModelFactory::createDomain($response['domain']),
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
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_UPDATE_CONFIGURATION,
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
    public function deleteDomain(
        string $uid,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_DELETE_CONFIGURATION,
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
