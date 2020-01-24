<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\PhoneType;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\PhoneType;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Data\PhoneType
 */
class Client extends
    AbstractClient
{
    /**
     * getAll
     *
     * @param string $jwt
     *
     * @return Response
     *
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function getAllPhoneTypes(
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'phoneTypes' => [
                    'type' => JsonRule::LIST_TYPE,
                    'schema' => Schema::PHONE_TYPE
                ]
            ]
        );

        $response = $this->get(
            vsprintf(
                '%s/data/phone/types',
                [
                    $this->host,
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
                'phoneTypes' => array_map(
                    static function ($phoneType) {
                        return ModelFactory::createPhoneTypeModel($phoneType);
                    },
                    $response['phoneTypes']
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
    public function getPhoneType(
        string $uid,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'phoneType' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::PHONE_TYPE
                ]
            ]
        );

        $response = $this->get(
            vsprintf(
                '%s/data/phone/types/%s',
                [
                    $this->host,
                    $uid,
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
                'phoneType' => null === $response['phoneType']
                    ? null
                    : ModelFactory::createPhoneTypeModel($response['phoneType']),
            ]
        );
    }

    /**
     * create
     *
     * @param PhoneType $phoneTypeModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function createPhoneType(
        PhoneType $phoneTypeModel,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'phoneType' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::PHONE_TYPE
                ]
            ]
        );

        $response = $this->post(
            vsprintf(
                '%s/data/phone/types',
                [
                    $this->host,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json' => [
                    'name' => $phoneTypeModel->getName(),
                ]
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'phoneType' => null === $response['phoneType']
                    ? null
                    : ModelFactory::createPhoneTypeModel($response['phoneType']),
            ]
        );
    }

    /**
     * update
     *
     * @param PhoneType $phoneTypeModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function updatePhoneType(
        PhoneType $phoneTypeModel,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );

        $response = $this->put(
            vsprintf(
                '%s/data/phone/types/%s',
                [
                    $this->host,
                    $phoneTypeModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json' => [
                    'name' => $phoneTypeModel->getName(),
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
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function deletePhoneType(
        string $uid,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );

        $response = $this->delete(
            vsprintf(
                '%s/data/phone/types/%s',
                [
                    $this->host,
                    $uid,
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
