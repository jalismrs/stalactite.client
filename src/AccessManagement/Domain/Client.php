<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\Domain;

use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AccessManagement\Client as ParentClient;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelationModel;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainUserRelationModel;
use Jalismrs\Stalactite\Client\AccessManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\AccessManagement\Schema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\DataManagement\Model\CustomerModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\UserModel;
use Jalismrs\Stalactite\Client\DataManagement\Schema as DataManagementSchema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\DomainModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/domains';
    
    /**
     * getRelations
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel $domainModel
     * @param string                                                       $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function getRelations(
        DomainModel $domainModel,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'   => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'     => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'relations' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'schema' => [
                        'users'     => [
                            'type'   => JsonRule::LIST_TYPE,
                            'schema' => [
                                'uid'  => [
                                    'type' => JsonRule::STRING_TYPE
                                ],
                                'user' => [
                                    'type'   => JsonRule::OBJECT_TYPE,
                                    'schema' => DataManagementSchema::MINIMAL_USER
                                ]
                            ]
                        ],
                        'customers' => [
                            'type'   => JsonRule::LIST_TYPE,
                            'schema' => [
                                'uid'      => [
                                    'type' => JsonRule::STRING_TYPE
                                ],
                                'customer' => [
                                    'type'   => JsonRule::OBJECT_TYPE,
                                    'schema' => DataManagementSchema::CUSTOMER
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        
        $response = $this->requestGet(
            vsprintf(
                '%s%s/%s/relations',
                [
                    $this->host,
                    self::API_URL_PART,
                    $domainModel->getUid(),
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
                'relations' => [
                    'users'     => array_map(
                        static function(array $relation) use ($domainModel): DomainUserRelationModel {
                            $domainUserRelationModel = ModelFactory::createDomainUserRelationModel($relation);
                            $domainUserRelationModel->setDomain($domainModel);
                            
                            return $domainUserRelationModel;
                        },
                        $response['relations']['users']
                    ),
                    'customers' => array_map(
                        static function(array $relation) use ($domainModel): DomainCustomerRelationModel {
                            $domainCustomerRelation = ModelFactory::createDomainCustomerRelationModel($relation);
                            $domainCustomerRelation->setDomain($domainModel);
                            
                            return $domainCustomerRelation;
                        },
                        $response['relations']['customers']
                    )
                ]
            ]
        );
    }
    
    /**
     * addUserRelation
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel $domainModel
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel   $userModel
     * @param string                                                       $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function addUserRelation(
        DomainModel $domainModel,
        UserModel $userModel,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'  => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'    => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'relation' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::DOMAIN_USER_RELATION
                ]
            ]
        );
        
        $response = $this->requestPost(
            vsprintf(
                '%s%s/%s/relations/users',
                [
                    $this->host,
                    self::API_URL_PART,
                    $domainModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => [
                    'user' => $userModel->getUid(),
                ]
            ],
            $schema
        );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'relation' =>  null === $response['relation']
                    ? null
                    : ModelFactory::createDomainUserRelationModel($response['relation']),
            ]
        );
    }
    
    /**
     * addCustomerRelation
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel   $domainModel
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\CustomerModel $customerModel
     * @param string                                                         $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function addCustomerRelation(
        DomainModel $domainModel,
        CustomerModel $customerModel,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'  => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'    => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'relation' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::DOMAIN_CUSTOMER_RELATION
                ]
            ]
        );
        
        $response = $this->requestPost(
            vsprintf(
                '%s%s/%s/relations/customers',
                [
                    $this->host,
                    self::API_URL_PART,
                    $domainModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => [
                    'customer' => $customerModel->getUid(),
                ]
            ],
            $schema
        );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'relation' => null === $response['relation']
                    ? null
                    : ModelFactory::createDomainCustomerRelationModel($response['relation']),
            ]
        );
    }
}
