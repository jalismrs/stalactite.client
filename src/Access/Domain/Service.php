<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;
use Jalismrs\Stalactite\Client\RequestConfiguration;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use function array_map;
use function assert;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\Domain
 */
class Service extends
    AbstractService
{
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
            'addCustomerRelation' => (new RequestConfiguration(
                '/access/domains/%s/relations/customers'
            ))
                ->setMethod('POST')
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'relation' => $response['relation'] === null
                                ? null
                                : ModelFactory::createDomainCustomerRelation($response['relation']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'relation' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'null'   => true,
                            'schema' => Schema::DOMAIN_CUSTOMER_RELATION,
                        ],
                    ]
                ),
            'addUserRelation'     => (new RequestConfiguration(
                '/access/domains/%s/relations/users'
            ))
                ->setMethod('POST')
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'relation' => $response['relation'] === null
                                ? null
                                : ModelFactory::createDomainUserRelation($response['relation']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'relation' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'null'   => true,
                            'schema' => Schema::DOMAIN_USER_RELATION,
                        ],
                    ]
                ),
            'getRelations'        => (new RequestConfiguration(
                '/access/domains/%s/relations'
            ))
                ->setValidation(
                    [
                        'relations' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'schema' => [
                                'users'     => [
                                    'type'   => JsonRule::LIST_TYPE,
                                    'schema' => [
                                        'uid'  => [
                                            'type' => JsonRule::STRING_TYPE,
                                        ],
                                        'user' => [
                                            'type'   => JsonRule::OBJECT_TYPE,
                                            'schema' => DataSchema::USER,
                                        ],
                                    ],
                                ],
                                'customers' => [
                                    'type'   => JsonRule::LIST_TYPE,
                                    'schema' => [
                                        'uid'      => [
                                            'type' => JsonRule::STRING_TYPE,
                                        ],
                                        'customer' => [
                                            'type'   => JsonRule::OBJECT_TYPE,
                                            'schema' => DataSchema::CUSTOMER,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ]
                ),
        ];
    }
    
    /**
     * getRelations
     *
     * @param Domain $domainModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws RequestConfigurationException
     * @throws SerializerException
     */
    public function getRelations(
        Domain $domainModel,
        string $jwt
    ) : Response {
        $requestConfiguration = $this->requestConfigurations['getRelations'];
        assert($requestConfiguration instanceof RequestConfiguration);
        
        $requestConfiguration->setResponse(
            static function(array $response) use ($domainModel) : array {
                return [
                    'relations' => [
                        'users'     => array_map(
                            static function(array $relation) use ($domainModel): DomainUserRelation {
                                $domainUserRelationModel = ModelFactory::createDomainUserRelation($relation);
                                $domainUserRelationModel->setDomain($domainModel);
                                
                                return $domainUserRelationModel;
                            },
                            $response['relations']['users']
                        ),
                        'customers' => array_map(
                            static function(array $relation) use ($domainModel): DomainCustomerRelation {
                                $domainCustomerRelation = ModelFactory::createDomainCustomerRelation($relation);
                                $domainCustomerRelation->setDomain($domainModel);
                                
                                return $domainCustomerRelation;
                            },
                            $response['relations']['customers']
                        )
                    ],
                ];
            }
        );
        
        return $this
            ->getClient()
            ->request(
                $requestConfiguration,
                [
                    $domainModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
    }
    
    /**
     * addUserRelation
     *
     * @param Domain $domainModel
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
    public function addUserRelation(
        Domain $domainModel,
        User $userModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['addUserRelation'],
                [
                    $domainModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => [
                        'user' => $userModel->getUid(),
                    ],
                ]
            );
    }
    
    /**
     * addCustomerRelation
     *
     * @param Domain   $domainModel
     * @param Customer $customerModel
     * @param string   $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function addCustomerRelation(
        Domain $domainModel,
        Customer $customerModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['addCustomerRelation'],
                [
                    $domainModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => [
                        'customer' => $customerModel->getUid(),
                    ],
                ]
            );
    }
}
