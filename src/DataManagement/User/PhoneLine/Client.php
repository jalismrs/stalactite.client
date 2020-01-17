<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\User\PhoneLine;

use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Model\PhoneLineModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\PhoneTypeModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\UserModel;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\DataManagement\User\Client as ParentClient;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

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
     * getAll
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel $userModel
     * @param string                                                     $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function getAll(
        UserModel $userModel,
        string $jwt
    ) : Response {
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
            vsprintf(
                '%s%s/%s%s',
                [
                    $this->host,
                    ParentClient::API_URL_PART,
                    $userModel->getUid(),
                    self::API_URL_PART,
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
                'phoneLines' => array_map(
                    static function($phoneLine) {
                        return ModelFactory::createPhoneLineModel($phoneLine);
                    },
                    $response['phoneLines']
                )
            ]
        );
    }
    
    /**
     * add
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel      $userModel
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\PhoneLineModel $phoneLineModel
     * @param string                                                          $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function add(
        UserModel $userModel,
        PhoneLineModel $phoneLineModel,
        string $jwt
    ) : Response {
        if (!$phoneLineModel->getType() instanceof PhoneTypeModel) {
            throw new ClientException(
                'Phone Line type must be a Phone Type',
                ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
            );
        }
        
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
            vsprintf(
                '%s%s/%s%s',
                [
                    $this->host,
                    ParentClient::API_URL_PART,
                    $userModel->getUid(),
                    self::API_URL_PART,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => [
                    'value' => $phoneLineModel->getValue(),
                    'type'  => [
                        'uid' => $phoneLineModel
                            ->getType()
                            ->getUid(),
                    ],
                ]
            ],
            $schema
        );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
    
    /**
     * remove
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel      $userModel
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\PhoneLineModel $phoneLineModel
     * @param string                                                          $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function remove(
        UserModel $userModel,
        PhoneLineModel $phoneLineModel,
        string $jwt
    ) : Response {
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
            vsprintf(
                '%s%s/%s%s/%s',
                [
                    $this->host,
                    ParentClient::API_URL_PART,
                    $userModel->getUid(),
                    self::API_URL_PART,
                    $phoneLineModel->getUid(),
                ],
            ),
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
