<?php

namespace jalismrs\Stalactite\Client\Authentication;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use jalismrs\Stalactite\Client\ClientException;
use function array_merge;

class TrustedAppClient extends AbstractClient
{
    public const API_URL_PREFIX = '/trustedApps';

    /**
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     */
    public function getAll(string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'trustedApps' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::TRUSTED_APP]
        ]);

        return $this->request('GET', $this->apiHost . Client::API_URL_PREFIX . self::API_URL_PREFIX, ['headers' => ['X-API-TOKEN' => $jwt]], $schema);
    }

    /**
     * @param string $uid
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(string $uid, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'trustedApps' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::TRUSTED_APP, 'null' => true]
        ]);

        return $this->request('GET', $this->apiHost . Client::API_URL_PREFIX . self::API_URL_PREFIX . '/' . $uid, ['headers' => ['X-API-TOKEN' => $jwt]], $schema);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(TrustedApp $trustedApp, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('PUT', $this->apiHost . Client::API_URL_PREFIX . self::API_URL_PREFIX . '/' . $trustedApp->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => [
                'name' => $trustedApp->getName(),
                'googleOAuthClientId' => $trustedApp->getGoogleOAuthClientId()
            ]
        ], $schema);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(TrustedApp $trustedApp, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'trustedApp' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => array_merge(
                Schema::TRUSTED_APP,
                ['resetToken' => ['type' => JsonRule::STRING_TYPE]]
            )]
        ]);

        return $this->request('POST', $this->apiHost . Client::API_URL_PREFIX . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => [
                'name' => $trustedApp->getName(),
                'googleOAuthClientId' => $trustedApp->getGoogleOAuthClientId()
            ]
        ], $schema);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function delete(TrustedApp $trustedApp, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('DELETE', $this->apiHost . Client::API_URL_PREFIX . self::API_URL_PREFIX . '/' . $trustedApp->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => [
                'resetToken' => $trustedApp->getResetToken()
            ]
        ], $schema);
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function resetAuthToken(TrustedApp $trustedApp, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'trustedApp' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::TRUSTED_APP]
        ]);

        return $this->request('PUT', $this->apiHost . Client::API_URL_PREFIX . self::API_URL_PREFIX . '/' . $trustedApp->getUid() . '/authToken/reset', [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => [
                'resetToken' => $trustedApp->getResetToken()
            ]
        ], $schema);
    }
}