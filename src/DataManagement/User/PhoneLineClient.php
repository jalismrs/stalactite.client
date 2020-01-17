<?php

namespace jalismrs\Stalactite\Client\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneLine;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneType;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\DataManagement\Schema;
use jalismrs\Stalactite\Client\Response;

class PhoneLineClient extends AbstractClient
{
    public const API_URL_PREFIX = '/phone/lines';

    /**
     * @param User $user
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(User $user, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'phoneLines' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::PHONE_LINE]
        ]);

        $r = $this->request('GET', $this->apiHost . UserClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $phoneLines = [];
        foreach ($r['phoneLines'] as $phoneLine) {
            $phoneLines[] = ModelFactory::createPhoneLine($phoneLine);
        }

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'phoneLines' => $phoneLines
        ]);

        return $response;
    }

    /**
     * @param User $user
     * @param PhoneLine $phoneLine
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addPhoneLine(User $user, PhoneLine $phoneLine, string $jwt): Response
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
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
        ]);

        $r = $this->request('POST', $this->apiHost . UserClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error']);

        return $response;
    }

    /**
     * @param User $user
     * @param PhoneLine $phoneLine
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removePhoneLine(User $user, PhoneLine $phoneLine, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        $r = $this->request('DELETE', $this->apiHost . UserClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX . '/' . $phoneLine->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error']);

        return $response;
    }
}