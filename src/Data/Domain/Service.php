<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Request;
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
     * getAllDomains
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
    public function getAllDomains(
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/domains'
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
                    )
            );
    }
    
    /**
     * getDomain
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
    public function getDomain(
        string $uid,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/domains/%s'
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
                                'domain' => $response['domain'] === null
                                    ? null
                                    : ModelFactory::createDomain($response['domain']),
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
                            'domain' => [
                                'type'   => JsonRule::OBJECT_TYPE,
                                'null'   => true,
                                'schema' => Schema::DOMAIN,
                            ],
                        ]
                    )
            );
    }
    
    /**
     * getByName
     *
     * @param string $name
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getByName(
        string $name,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/domains'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'query'   => [
                                'name' => $name
                            ]
                        ]
                    )
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
                    )
            );
    }
    
    /**
     * getByNameAndApiKey
     *
     * @param string $name
     * @param string $apiKey
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getByNameAndApiKey(
        string $name,
        string $apiKey,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/domains'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'query'   => [
                                'name'   => $name,
                                'apiKey' => $apiKey
                            ]
                        ]
                    )
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
                    )
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
                (new Request(
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
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'json'    => $domainModel,
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
                    )
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
                (new Request(
                    '/data/domains/%s'
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
                            'json'    => $domainModel,
                        ]
                    )
                    ->setUriDatas(
                        [
                            $domainModel->getUid(),
                        ]
                    )
            );
    }
    
    /**
     * deleteDomain
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
    public function deleteDomain(
        string $uid,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/domains/%s'
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
                    ),
                );
    }
}
