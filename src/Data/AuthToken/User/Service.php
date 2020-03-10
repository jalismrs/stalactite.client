<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\User;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Class Service
 * @package Jalismrs\Stalactite\Client\Data\AuthToken\User
 */
class Service extends AbstractService
{
    /**
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getAllUsers(string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/users');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::USER, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn($user): User => ModelFactory::createUser($user),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt
        ]);
    }

    /**
     * @param string $email
     * @param string $googleId
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getByEmailAndGoogleId(string $email, string $googleId, string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/users');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::USER))
            ->setResponseFormatter(static fn(array $response): User => ModelFactory::createUser($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'query' => [
                'email' => $email,
                'googleId' => $googleId
            ]
        ]);
    }

    /**
     * @param string $uid
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getUser(string $uid, string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/users/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::USER))
            ->setResponseFormatter(static fn(array $response): User => ModelFactory::createUser($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$uid]
        ]);
    }
}
