<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\User\PhoneLine;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Model\PhoneLineModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\PhoneTypeModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\UserModel;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\Response;
use \Jalismrs\Stalactite\Client\DataManagement\User\Client as ParentClient;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\UserModel\PhoneLineModel
 */
class Client extends
    ClientAbstract
{
    
    public const API_URL_PART = '/phone/lines';
    
    /**
     * @param UserModel $user
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(UserModel $user, string $jwt) : Response
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
                'phoneLines' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::PHONE_LINE
                ]
            ]
        );
    
        $response = $this->requestGet(
            $this->host . ParentClient::API_URL_PART . '/' . $user->getUid() . self::API_URL_PART,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        $phoneLines = [];
        foreach ($response['phoneLines'] as $phoneLine) {
            $phoneLines[] = ModelFactory::createPhoneLine($phoneLine);
        }
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'phoneLines' => $phoneLines
            ]
        );
    }
    
    /**
     * @param UserModel      $user
     * @param PhoneLineModel $phoneLine
     * @param string         $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addPhoneLine(UserModel $user, PhoneLineModel $phoneLine, string $jwt) : Response
    {
        if (!$phoneLine->getType() instanceof PhoneTypeModel) {
            throw new ClientException(
                'Phone Line type must be a Phone Type',
                ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
            );
        }
        
        $body = [
            'value' => $phoneLine->getValue(),
            'type'  => [
                'uid' => $phoneLine->getType()
                                   ->getUid()
            ]
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
                ],
            ]
        );
    
        $response = $this->requestPost(
            $this->host . ParentClient::API_URL_PART . '/' . $user->getUid() . self::API_URL_PART,
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
     * @param UserModel      $user
     * @param PhoneLineModel $phoneLine
     * @param string         $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removePhoneLine(UserModel $user, PhoneLineModel $phoneLine, string $jwt) : Response
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
            $this->host . ParentClient::API_URL_PART . '/' . $user->getUid() . self::API_URL_PART . '/' . $phoneLine->getUid(),
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
