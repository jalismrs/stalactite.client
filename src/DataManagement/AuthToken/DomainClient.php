<?php

namespace jalismrs\Stalactite\Client\DataManagement\AuthToken;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\DataManagement\Schema;
use jalismrs\Stalactite\Client\Response;

class DomainClient extends AbstractClient
{
    public const API_URL_PREFIX = AuthTokenClient::API_URL_PREFIX . '/domains';

    /**
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(string $apiAuthToken): Response
    {
        $jwt = AuthTokenClient::generateJwt($apiAuthToken, $this->userAgent);

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'domains' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::DOMAIN]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => (string)$jwt]
        ], $schema);

        $domains = [];
        foreach ($r['domains'] as $domain) {
            $domains[] = ModelFactory::createDomain($domain);
        }

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'domains' => $domains
        ]);

        return $response;
    }

    /**
     * @param string $name
     * @param string $apiKey
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByNameAndApiKey(string $name, string $apiKey, string $apiAuthToken): Response
    {
        $jwt = AuthTokenClient::generateJwt($apiAuthToken, $this->userAgent);

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'domains' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::DOMAIN]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => (string)$jwt],
            'query' => [
                'name' => $name,
                'apiKey' => $apiKey
            ]
        ], $schema);

        $domains = [];
        foreach ($r['domains'] as $domain) {
            $domains[] = ModelFactory::createDomain($domain);
        }

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'domains' => $domains
        ]);

        return $response;
    }

    /**
     * @param string $name
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByName(string $name, string $apiAuthToken): Response
    {
        $jwt = AuthTokenClient::generateJwt($apiAuthToken, $this->userAgent);

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'domains' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::DOMAIN]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => (string)$jwt],
            'query' => ['name' => $name]
        ], $schema);

        $domains = [];
        foreach ($r['domains'] as $domain) {
            $domains[] = ModelFactory::createDomain($domain);
        }

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'domains' => $domains
        ]);

        return $response;
    }

    /**
     * @param string $uid
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(string $uid, string $apiAuthToken): Response
    {
        $jwt = AuthTokenClient::generateJwt($apiAuthToken, $this->userAgent);

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'domain' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::DOMAIN]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $uid, [
            'headers' => ['X-API-TOKEN' => (string)$jwt]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'domain' => $r['domain'] ? ModelFactory::createDomain($r['domain']) : null
        ]);

        return $response;
    }
}