<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\DataManagement;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneType;
use jalismrs\Stalactite\Client\Response;

/**
 * PhoneTypeClient
 *
 * @package jalismrs\Stalactite\Client\DataManagement
 */
class PhoneTypeClient extends
    AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/phone/types';
    
    /**
     * getAll
     *
     * @param string $jwt
     *
     * @return \jalismrs\Stalactite\Client\Response
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    public function getAll(string $jwt) : Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'    => ['type' => JsonRule::BOOLEAN_TYPE],
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
    
        $r = $this->requestGet(
            $this->apiHost . self::API_URL_PREFIX,
            [
                'headers' => ['X-API-TOKEN' => $jwt]
            ],
            $schema
        );
        
        $phoneTypes = [];
        foreach ($r['phoneTypes'] as $phoneType) {
            $phoneTypes[] = ModelFactory::createPhoneType($phoneType);
        }
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error'])
            ->setData(
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
                'success'   => ['type' => JsonRule::BOOLEAN_TYPE],
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
    
        $r = $this->requestGet(
            $this->apiHost . self::API_URL_PREFIX . '/' . $uid,
            [
                'headers' => ['X-API-TOKEN' => $jwt]
            ],
            $schema
        );
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error'])
            ->setData(
                [
                    'phoneType' => $r['phoneType']
                        ? ModelFactory::createPhoneType($r['phoneType'])
                        : null
                ]
            );
    }
    
    /**
     * @param PhoneType $phoneType
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(PhoneType $phoneType, string $jwt) : Response
    {
        $body = [
            'name' => $phoneType->getName()
        ];
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'   => ['type' => JsonRule::BOOLEAN_TYPE],
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
    
        $r = $this->requestPost(
            $this->apiHost . self::API_URL_PREFIX,
            [
                'headers' => ['X-API-TOKEN' => $jwt],
                'json'    => $body
            ],
            $schema
        );
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error'])
            ->setData(
                [
                    'phoneType' => $r['phoneType']
                        ? ModelFactory::createPhoneType($r['phoneType'])
                        : null
                ]
            );
    }
    
    /**
     * @param PhoneType $phoneType
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(PhoneType $phoneType, string $jwt) : Response
    {
        $body = [
            'name' => $phoneType->getName()
        ];
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => ['type' => JsonRule::BOOLEAN_TYPE],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );
    
        $r = $this->requestPut(
            $this->apiHost . self::API_URL_PREFIX . '/' . $phoneType->getUid(),
            [
                'headers' => ['X-API-TOKEN' => $jwt],
                'json'    => $body
            ],
            $schema
        );
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error']);
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
                'success' => ['type' => JsonRule::BOOLEAN_TYPE],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );
    
        $r = $this->requestDelete(
            $this->apiHost . self::API_URL_PREFIX . '/' . $uid,
            [
                'headers' => ['X-API-TOKEN' => $jwt]
            ],
            $schema
        );
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error']);
    }
}
