<?php

namespace jalismrs\Stalactite\Client\DataManagement;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\Domain;

class DomainClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/domains';

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
            'domains' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::DOMAIN]
        ]);

        return $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(Domain $domain, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'domain' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::DOMAIN]
        ]);

        return $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $domain->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(Domain $domain, string $jwt): array
    {
        $body = [
            'name' => $domain->getName(),
            'type' => $domain->getType(),
            'externalAuth' => $domain->getExternalAuth(),
            'apiKey' => $domain->getApiKey(),
            'generationDate' => $domain->getGenerationDate()
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'domain' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::DOMAIN]
        ]);

        return $this->request('POST', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);
    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(Domain $domain, string $jwt): array
    {
        $body = [
            'name' => $domain->getName(),
            'type' => $domain->getType(),
            'externalAuth' => $domain->getExternalAuth(),
            'apiKey' => $domain->getApiKey(),
            'generationDate' => $domain->getGenerationDate()
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('PUT', $this->apiHost . self::API_URL_PREFIX . '/' . $domain->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);
    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function delete(Domain $domain, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('DELETE', $this->apiHost . self::API_URL_PREFIX . '/' . $domain->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }
}