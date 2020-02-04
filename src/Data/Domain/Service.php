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
use function vsprintf;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\DOmain
 */
class Service extends
    AbstractService
{
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
                'domains' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::DOMAIN
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->get(
                '/data/domains',
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
                'domain'  => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::DOMAIN
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->get(
                vsprintf(
                    '/data/domains/%s',
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
    public function getByName(string $name, string $jwt) : Response
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
                'domains' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::DOMAIN
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->get(
                '/data/domains',
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'query'   => [
                        'name' => $name
                    ]
                ],
                $schema
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
    public function getByNameAndApiKey(string $name, string $apiKey, string $jwt) : Response
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
                'domains' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::DOMAIN
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->get(
                '/data/domains',
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'query'   => [
                        'name'   => $name,
                        'apiKey' => $apiKey
                    ]
                ],
                $schema
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
                'domain'  => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::DOMAIN
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->post(
                '/data/domains',
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $this
                        ->getClient()
                        ->getSerializer()
                        ->normalize(
                            $domainModel,
                            [
                                AbstractNormalizer::GROUPS => [
                                    'create',
                                ],
                            ]
                        )
                ],
                $schema
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
                    '/data/domains/%s',
                    [
                        $domainModel->getUid(),
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
                            $domainModel,
                            [
                                AbstractNormalizer::GROUPS => [
                                    'update',
                                ],
                            ]
                        )
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
    public function deleteDomain(
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
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->delete(
                vsprintf(
                    '/data/domains/%s',
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
