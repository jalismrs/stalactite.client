<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\PhoneType;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Model\PhoneTypeModel;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\DataManagement\Client as ParentClient;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\PhoneTypeModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/phone/types';
    
    /**
     * getAll
     *
     * @param string $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function getAll(string $jwt) : Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'    => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'      => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'phoneTypes' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::PHONE_TYPE
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
        
        $phoneTypes = [];
        foreach ($response['phoneTypes'] as $phoneType) {
            $phoneTypes[] = ModelFactory::createPhoneType($phoneType);
        }
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'phoneTypes' => $phoneTypes
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
                'success'   => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'     => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'phoneType' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::PHONE_TYPE
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
                'phoneType' => $response['phoneType']
                    ? ModelFactory::createPhoneType($response['phoneType'])
                    : null
            ]
        );
    }
    
    /**
     * @param PhoneTypeModel $phoneType
     * @param string         $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(PhoneTypeModel $phoneType, string $jwt) : Response
    {
        $body = [
            'name' => $phoneType->getName()
        ];
        
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
                'phoneType' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::PHONE_TYPE
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
                'phoneType' => $response['phoneType']
                    ? ModelFactory::createPhoneType($response['phoneType'])
                    : null
            ]
        );
    }
    
    /**
     * @param PhoneTypeModel $phoneType
     * @param string         $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(PhoneTypeModel $phoneType, string $jwt) : Response
    {
        $body = [
            'name' => $phoneType->getName()
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
            $this->host . self::API_URL_PART . '/' . $phoneType->getUid(),
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
