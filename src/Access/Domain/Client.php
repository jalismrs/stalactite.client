<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Domain;

use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\Access\Client as ParentClient;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelationModel;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelationModel;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\Data\Model\CustomerModel;
use Jalismrs\Stalactite\Client\Data\Model\DomainModel;
use Jalismrs\Stalactite\Client\Data\Model\UserModel;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Access\DomainModel
 */
class Client extends
    ClientAbstract
{
    /**
     * getRelations
     *
     * @param \Jalismrs\Stalactite\Client\Data\Model\DomainModel $domainModel
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
                                    'schema' => DataSchema::MINIMAL_USER
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
                                    'schema' => DataSchema::CUSTOMER
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );
        
        $response = $this->requestGet(
            vsprintf(
                '%s/access/domains/%s/relations',
                [
                    $this->host,
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
     * @param \Jalismrs\Stalactite\Client\Data\Model\DomainModel $domainModel
     * @param \Jalismrs\Stalactite\Client\Data\Model\UserModel   $userModel
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
                '%s/access/domains/%s/relations/users',
                [
                    $this->host,
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
     * @param \Jalismrs\Stalactite\Client\Data\Model\DomainModel   $domainModel
     * @param \Jalismrs\Stalactite\Client\Data\Model\CustomerModel $customerModel
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
                '%s/access/domains/%s/relations/customers',
                [
                    $this->host,
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
