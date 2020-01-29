<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Data\AuthToken\Domain
 */
class Client extends
    AbstractClient
{
    /**
     * getAllDomains
     *
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllDomains(
        string $apiAuthToken
    ): Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this->getUserAgent()
        );

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
                'domains' => [
                    'type' => JsonRule::LIST_TYPE,
                    'schema' => Schema::DOMAIN
                ]
            ]
        );

        $response = $this->get(
            vsprintf(
                '%s/data/auth-token/domains',
                [
                    $this->getHost(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => (string)$jwt
                ]
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'domains' => array_map(
                    static function ($domain) {
                        return ModelFactory::createDomain($domain);
                    },
                    $response['domains']
                )
            ]
        );
    }

    /**
     * @param string $name
     * @param string $apiKey
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByNameAndApiKey(
        string $name,
        string $apiKey,
        string $apiAuthToken
    ): Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this->getUserAgent()
        );

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
                'domains' => [
                    'type' => JsonRule::LIST_TYPE,
                    'schema' => Schema::DOMAIN
                ]
            ]
        );

        $response = $this->get(
            vsprintf(
                '%s/data/auth-token/domains',
                [
                    $this->getHost(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => (string)$jwt
                ],
                'query' => [
                    'name' => $name,
                    'apiKey' => $apiKey
                ]
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'domains' => array_map(
                    static function ($domain) {
                        return ModelFactory::createDomain($domain);
                    },
                    $response['domains']
                )
            ]
        );
    }

    /**
     * @param string $name
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByName(
        string $name,
        string $apiAuthToken
    ): Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this->getUserAgent()
        );

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
                'domains' => [
                    'type' => JsonRule::LIST_TYPE,
                    'schema' => Schema::DOMAIN
                ]
            ]
        );

        $response = $this->get(
            vsprintf(
                '%s/data/auth-token/domains',
                [
                    $this->getHost(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => (string)$jwt
                ],
                'query' => [
                    'name' => $name
                ]
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'domains' => array_map(
                    static function ($domain) {
                        return ModelFactory::createDomain($domain);
                    },
                    $response['domains']
                )
            ]
        );
    }

    /**
     * @param string $uid
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getDomain(
        string $uid,
        string $apiAuthToken
    ): Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this->getUserAgent()
        );

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
                'domain' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::DOMAIN
                ]
            ]
        );

        $response = $this->get(
            vsprintf(
                '%s/data/auth-token/domains/%s',
                [
                    $this->getHost(),
                    $uid,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => (string)$jwt
                ]
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'domain' => null === $response['domain']
                    ? null
                    : ModelFactory::createDomain($response['domain']),
            ]
        );
    }
}
