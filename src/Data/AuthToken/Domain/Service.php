<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Domain;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Class Service
 * @package Jalismrs\Stalactite\Client\Data\AuthToken\Domain
 */
class Service extends AbstractService
{
    /**
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getAllDomains(string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/domains');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn($domain): Domain => ModelFactory::createDomain($domain),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt
        ]);
    }

    /**
     * @param string $name
     * @param string $apiKey
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getByNameAndApiKey(string $name, string $apiKey, string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/domains');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn($domain): Domain => ModelFactory::createDomain($domain),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'query' => [
                'name' => $name,
                'apiKey' => $apiKey
            ]
        ]);
    }

    /**
     * @param string $name
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getByName(string $name, string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/domains');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn($domain): Domain => ModelFactory::createDomain($domain),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'query' => [
                'name' => $name
            ]
        ]);
    }

    /**
     * @param string $uid
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getDomain(string $uid, string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/domains/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN))
            ->setResponseFormatter(static fn(array $response): Domain => ModelFactory::createDomain($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$uid]
        ]);
    }
}
