<?php

namespace jalismrs\Stalactite\Client\Authentication;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\Authentication\Model\ModelFactory;
use jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\Response;
use function array_merge;

class TrustedAppClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/trustedApps';

    /**
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'trustedApps' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::TRUSTED_APP]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX, ['headers' => ['X-API-TOKEN' => $jwt]], $schema);

        $trustedApps = [];
        foreach ($r['trustedApps'] as $trustedApp) {
            $trustedApps[] = ModelFactory::createTrustedApp($trustedApp);
        }

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'trustedApps' => $trustedApps
        ]);

        return $response;
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(TrustedApp $trustedApp, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'trustedApp' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::TRUSTED_APP, 'null' => true]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $trustedApp->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'trustedApp' => ModelFactory::createTrustedApp($r['trustedApp'])
        ]);

        return $response;
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(TrustedApp $trustedApp, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        $r = $this->request('PUT', $this->apiHost . self::API_URL_PREFIX . '/' . $trustedApp->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => [
                'name' => $trustedApp->getName(),
                'googleOAuthClientId' => $trustedApp->getGoogleOAuthClientId()
            ]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error']);

        return $response;
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(TrustedApp $trustedApp, string $jwt): Response
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

        $r = $this->request('POST', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => [
                'name' => $trustedApp->getName(),
                'googleOAuthClientId' => $trustedApp->getGoogleOAuthClientId()
            ]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'trustedApp' => ModelFactory::createTrustedApp($r['trustedApp'])
        ]);

        return $response;
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function delete(TrustedApp $trustedApp, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        $r = $this->request('DELETE', $this->apiHost . self::API_URL_PREFIX . '/' . $trustedApp->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => [
                'resetToken' => $trustedApp->getResetToken()
            ]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error']);

        return $response;
    }

    /**
     * @param TrustedApp $trustedApp
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function resetAuthToken(TrustedApp $trustedApp, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'trustedApp' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::TRUSTED_APP]
        ]);

        $r = $this->request('PUT', $this->apiHost . self::API_URL_PREFIX . '/' . $trustedApp->getUid() . '/authToken/reset', [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => [
                'resetToken' => $trustedApp->getResetToken()
            ]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'trustedApp' => ModelFactory::createTrustedApp($r['trustedApp'])
        ]);

        return $response;
    }
}