<?php

namespace jalismrs\Stalactite\Client\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneLine;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneType;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\DataManagement\Schema;

class MeClient extends AbstractClient
{
    public const API_URL_PREFIX = UserClient::API_URL_PREFIX . '/me';

    /**
     * @param string $jwt
     * @return array
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function get(string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'me' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::USER]
        ]);

        return $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(User $user, string $jwt): array
    {
        $body = [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'office' => $user->getOffice(),
            'birthday' => $user->getBirthday()
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('POST', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);
    }

    /**
     * @param PhoneLine $phoneLine
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addPhoneLine(PhoneLine $phoneLine, string $jwt): array
    {
        if (!($phoneLine->getType() instanceof PhoneType)) {
            throw new ClientException('Phone Line type must be a Phone Type', ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT);
        }

        $body = [
            'value' => $phoneLine->getValue(),
            'type' => [
                'uid' => $phoneLine->getType()->getUid()
            ]
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('POST', $this->apiHost . self::API_URL_PREFIX . '/phone/lines', [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);
    }

    /**
     * @param PhoneLine $phoneLine
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removePhoneLine(PhoneLine $phoneLine, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('DELETE', $this->apiHost . self::API_URL_PREFIX . '/phone/lines/' . $phoneLine->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }
}