<?php

namespace jalismrs\Stalactite\Client\DataManagement;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneType;

class PhoneTypeClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/phone/types';

    /**
     * @param string $jwt
     * @return array
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function getAll(string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'phoneTypes' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::PHONE_TYPE]
        ]);

        return $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }

    /**
     * @param PhoneType $phoneType
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(PhoneType $phoneType, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'phoneType' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::PHONE_TYPE]
        ]);

        return $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $phoneType->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }

    /**
     * @param PhoneType $phoneType
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(PhoneType $phoneType, string $jwt): array
    {
        $body = [
            'name' => $phoneType->getName()
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'phoneType' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::PHONE_TYPE]
        ]);

        return $this->request('POST', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);
    }

    /**
     * @param PhoneType $phoneType
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(PhoneType $phoneType, string $jwt): array
    {
        $body = [
            'name' => $phoneType->getName()
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('PUT', $this->apiHost . self::API_URL_PREFIX . '/' . $phoneType->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);
    }

    /**
     * @param PhoneType $phoneType
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function delete(PhoneType $phoneType, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('DELETE', $this->apiHost . self::API_URL_PREFIX . '/' . $phoneType->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }
}