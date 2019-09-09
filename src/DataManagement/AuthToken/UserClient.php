<?php

namespace jalismrs\Stalactite\Client\DataManagement\AuthToken;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\Response;

class UserClient extends AbstractClient
{
    /**
     * @param string $apiAuthToken
     * @param User $user
     * @return Response
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function getByEmailAndGoogleId(User $user, string $apiAuthToken): Response
    {
        $jwt = AuthTokenClient::generateJwt($apiAuthToken, $this->userAgent);
        $body = [
            'email' => $user->getEmail(),
            'googleId' => $user->getGoogleId()
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'user' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => [
                'uid' => ['type' => JsonRule::STRING_TYPE],
                'type' => ['type' => JsonRule::STRING_TYPE, 'enum' => ['user', 'customer']],
                'privilege' => ['type' => JsonRule::STRING_TYPE]
            ]]
        ]);

        $r = $this->request('POST', $this->apiHost . AuthTokenClient::API_URL_PREFIX . '/user', [
            'headers' => ['X-API-TOKEN' => (string)$jwt],
            'json' => $body
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'user' => $r['user']
        ]);

        return $response;
    }
}