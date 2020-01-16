<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\AccessManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\AccessManagement\Schema;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\CustomerModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\UserModel;
use Jalismrs\Stalactite\Client\DataManagement\Schema as DataManagementSchema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\AccessManagement\Client as ParentClient;
use function array_map;

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
     * @param DomainModel $domain
     * @param string      $jwt
     *
     * @return Response
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function getRelations(DomainModel $domain, string $jwt) : Response
    {
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
            $this->host . self::API_URL_PART . '/' . $domain->getUid() . '/relations',
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
                        static function($relation) use ($domain) {
                            return ModelFactory::createDomainUserRelation($relation)
                                               ->setDomain($domain);
                        },
                        $response['relations']['users']
                    ),
                    'customers' => array_map(
                        static function($relation) use ($domain) {
                            return ModelFactory::createDomainCustomerRelation($relation)
                                               ->setDomain($domain);
                        },
                        $response['relations']['customers']
                    )
                ]
            ]
        );
    }
    
    /**
     * @param DomainModel $domain
     * @param UserModel   $user
     * @param string      $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addUserRelation(
        DomainModel $domain,
        UserModel $user,
        string $jwt
    ) : Response
    {
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
            $this->host . self::API_URL_PART . '/' . $domain->getUid() . '/relations/users',
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => [
                    'user' => $user->getUid()
                ]
            ],
            $schema
        );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'relation' => $response['relation']
                    ? ModelFactory::createDomainUserRelation($response['relation'])
                    : null
            ]
        );
    }
    
    /**
     * @param DomainModel   $domain
     * @param CustomerModel $customer
     * @param string        $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addCustomerRelation(
        DomainModel $domain,
        CustomerModel $customer,
        string $jwt
    ) : Response
    {
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
            $this->host . self::API_URL_PART . '/' . $domain->getUid() . '/relations/customers',
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => [
                    'customer' => $customer->getUid()
                ]
            ],
            $schema
        );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'relation' => $response['relation']
                    ? ModelFactory::createDomainCustomerRelation($response['relation'])
                    : null
            ]
        );
    }
}
