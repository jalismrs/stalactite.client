<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Customer;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Class Service
 * @package Jalismrs\Stalactite\Client\Data\AuthToken\Customer
 */
class Service extends AbstractService
{
    /**
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getAllCustomers(string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/customers');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::CUSTOMER, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn(array $customer): Customer => ModelFactory::createCustomer($customer),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, ['jwt' => (string)$jwt]);
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

        $endpoint = new Endpoint('/data/auth-token/customers');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::CUSTOMER))
            ->setResponseFormatter(static fn(array $response): Customer => ModelFactory::createCustomer($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'query' => [
                'email' => $email,
                'googleId' => $googleId
            ]
        ]);
    }

    /**
     * @param string $email
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getByEmail(string $email, string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/customers');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::CUSTOMER))
            ->setResponseFormatter(static fn(array $response): Customer => ModelFactory::createCustomer($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'query' => ['email' => $email]
        ]);
    }

    /**
     * @param string $uid
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getCustomer(string $uid, string $apiAuthToken): Response
    {
        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/data/auth-token/customers/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::CUSTOMER))
            ->setResponseFormatter(static function (array $response): Customer {
                return ModelFactory::createCustomer($response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$uid]
        ]);
    }
}
