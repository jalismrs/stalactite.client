<?php

namespace jalismrs\Stalactite\Client\DataManagement;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\Post;

class PostClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/posts';

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
            'posts' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::POST]
        ]);

        return $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(Post $post, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'post' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::POST]
        ]);

        return $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $post->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(Post $post, string $jwt): array
    {
        $body = [
            'privilege' => $post->getPrivilege(),
            'name' => $post->getName(),
            'shortName' => $post->getShortName(),
            'rank' => $post->getRank()
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'post' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::POST]
        ]);

        return $this->request('POST', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);
    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(Post $post, string $jwt): array
    {
        $body = [
            'privilege' => $post->getPrivilege(),
            'name' => $post->getName(),
            'shortName' => $post->getShortName(),
            'rank' => $post->getRank()
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('PUT', $this->apiHost . self::API_URL_PREFIX . '/' . $post->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);
    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function delete(Post $post, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('DELETE', $this->apiHost . self::API_URL_PREFIX . '/' . $post->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }

    /**
     * @param Post $post
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getUsers(Post $post, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'users' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::USER]
        ]);

        return $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $post->getUid() . '/users', [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }
}