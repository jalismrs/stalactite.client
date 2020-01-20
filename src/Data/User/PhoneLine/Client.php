<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\PhoneLine;

use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\PhoneLineModel;
use Jalismrs\Stalactite\Client\Data\Model\PhoneTypeModel;
use Jalismrs\Stalactite\Client\Data\Model\UserModel;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Data\UserModel\PhoneLineModel
 */
class Client extends
    ClientAbstract
{
    /**
     * getAll
     *
     * @param \Jalismrs\Stalactite\Client\Data\Model\UserModel $userModel
     * @param string                                           $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function getAllPhoneLines(
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
        
        $response = $this->get(
            vsprintf(
                '%s/data/users/%s/phone/lines',
                [
                    $this->host,
                    $userModel->getUid(),
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
     * addPhoneLine
     *
     * @param \Jalismrs\Stalactite\Client\Data\Model\UserModel      $userModel
     * @param \Jalismrs\Stalactite\Client\Data\Model\PhoneLineModel $phoneLineModel
     * @param string                                                $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function addPhoneLine(
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
        
        $response = $this->post(
            vsprintf(
                '%s/data/users/%s/phone/lines',
                [
                    $this->host,
                    $userModel->getUid(),
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
     * removePhoneLine
     *
     * @param \Jalismrs\Stalactite\Client\Data\Model\UserModel      $userModel
     * @param \Jalismrs\Stalactite\Client\Data\Model\PhoneLineModel $phoneLineModel
     * @param string                                                $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function removePhoneLine(
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
        
        $response = $this->delete(
            vsprintf(
                '%s/data/users/%s/phone/lines/%s',
                [
                    $this->host,
                    $userModel->getUid(),
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
