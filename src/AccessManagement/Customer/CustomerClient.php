<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\AccessManagement\Client;
use Jalismrs\Stalactite\Client\AccessManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\AccessManagement\Schema;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\Customer;
use Jalismrs\Stalactite\Client\DataManagement\Model\Domain;
use Jalismrs\Stalactite\Client\DataManagement\Schema as DataManagementSchema;
use Jalismrs\Stalactite\Client\Response;

/***
 * Client
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\Customer
 */
class CustomerClient extends
    AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/customers';
    
    /**
     * me
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Customer\MeClient
     */
    public function me() : MeClient
    {
        static $client = null;
        
        if (null === $client) {
            $client = new MeClient(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * @param Customer $customer
     * @param string   $jwt
     *
     * @return Response
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function getRelations(
        Customer $customer,
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
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => [
                        'uid'    => [
                            'type' => JsonRule::STRING_TYPE
                        ],
                        'domain' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'schema' => DataManagementSchema::DOMAIN
                        ]
                    ]
                ]
            ]
        );
        
        $r = $this->requestGet(
            $this->host . self::API_URL_PREFIX . '/' . $customer->getUid() . '/relations',
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        $relations = [];
        foreach ($r['relations'] as $relation) {
            $relations[] = ModelFactory::createDomainCustomerRelation($relation)
                                       ->setCustomer($customer);
        }
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'relations' => $relations
            ]
        );
    }
    
    /**
     * @param Customer $customer
     * @param Domain   $domain
     * @param string   $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAccessClearance(Customer $customer, Domain $domain, string $jwt) : Response
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
                'clearance' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'schema' => Schema::ACCESS_CLEARANCE
                ]
            ]
        );
        
        $r = $this->requestGet(
            $this->host . self::API_URL_PREFIX . '/' . $customer->getUid() . '/access/' . $domain->getUid(),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'clearance' => ModelFactory::createAccessClearance($r['clearance'])
            ]
        );
    }
}
