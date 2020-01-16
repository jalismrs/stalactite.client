<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\CustomerModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\Response;
use \Jalismrs\Stalactite\Client\DataManagement\Client as ParentClient;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\CustomerModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/customers';
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * clientMe
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Customer\Me\Client
     */
    public function clientMe() : Me\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new Me\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(string $jwt) : Response
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
                'customers' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );
    
        $response = $this->requestGet(
            $this->host . self::API_URL_PART,
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
                'customers' => array_map(
                    static function($customer) {
                        return ModelFactory::createCustomer($customer);
                    },
                    $response['customers']
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
    public function get(string $uid, string $jwt) : Response
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
                'customer' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );
    
        $response = $this->requestGet(
            $this->host . self::API_URL_PART . '/' . $uid,
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
                'customer' => $response['customer']
                    ? ModelFactory::createCustomer($response['customer'])
                    : null
            ]
        );
    }
    
    /**
     * @param string $email
     * @param string $googleId
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByEmailAndGoogleId(string $email, string $googleId, string $jwt) : Response
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
                'customer' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );
    
        $response = $this->requestGet(
            $this->host . self::API_URL_PART,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'query'   => [
                    'email'    => $email,
                    'googleId' => $googleId
                ]
            ],
            $schema
        );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'customer' => $response['customer']
                    ? ModelFactory::createCustomer($response['customer'])
                    : null
            ]
        );
    }
    
    /**
     * @param CustomerModel $customer
     * @param string        $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(CustomerModel $customer, string $jwt) : Response
    {
        $body = [
            'firstName' => $customer->getFirstName(),
            'lastName'  => $customer->getLastName(),
            'email'     => $customer->getEmail()
        ];
        
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
                'customer' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );
    
        $response = $this->requestPost(
            $this->host . self::API_URL_PART,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
            ],
            $schema
        );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'customer' => $response['customer']
                    ? ModelFactory::createCustomer($response['customer'])
                    : null
            ]
        );
    }
    
    /**
     * @param CustomerModel $customer
     * @param string        $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(CustomerModel $customer, string $jwt) : Response
    {
        $body = [
            'firstName' => $customer->getFirstName(),
            'lastName'  => $customer->getLastName(),
            'email'     => $customer->getEmail()
        ];
        
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
    
        $response = $this->requestPut(
            $this->host . self::API_URL_PART . '/' . $customer->getUid(),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
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
    public function delete(string $uid, string $jwt) : Response
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
                ]
            ]
        );
    
        $response = $this->requestDelete(
            $this->host . self::API_URL_PART . '/' . $uid,
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
