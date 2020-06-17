<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Customer;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\Customer
 */
class Service extends AbstractService
{
    private ?Me\Service $serviceMe = null;

    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    public function me(): Me\Service
    {
        if ($this->serviceMe === null) {
            $this->serviceMe = new Me\Service($this->getClient());
        }

        return $this->serviceMe;
    }

    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getAllCustomers(Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/customers');
        $endpoint->setResponseValidationSchema(new JsonSchema(Customer::getSchema(), JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn(array $customer): Customer => ModelFactory::createCustomer($customer),
                    $response
                );
            });
        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt
        ]);
    }

    /**
     * @param string $email
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getByEmail(string $email, Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/customers');
        $endpoint->setResponseValidationSchema(new JsonSchema(Customer::getSchema()))
            ->setResponseFormatter(static fn(array $response): Customer => ModelFactory::createCustomer($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'query' => ['email' => $email]
        ]);
    }

    /**
     * @param string $uid
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getCustomer(string $uid, Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/customers/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Customer::getSchema()))
            ->setResponseFormatter(static fn(array $response): Customer => ModelFactory::createCustomer($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param Customer $customer
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     */
    public function createCustomer(Customer $customer, Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/customers', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema(Customer::getSchema()))
            ->setResponseFormatter(static fn(array $response): Customer => ModelFactory::createCustomer($response));

        $data = Normalizer::getInstance()->normalize($customer, [
            AbstractNormalizer::GROUPS => ['create']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'json' => $data
        ]);
    }

    /**
     * @param Customer $customer
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     */
    public function updateCustomer(Customer $customer, Token $jwt): Response
    {
        if ($customer->getUid() === null) {
            throw new DataServiceException('Customer lacks a uid', DataServiceException::MISSING_CUSTOMER_UID);
        }

        $endpoint = new Endpoint('/data/customers/%s', 'PUT');

        $data = Normalizer::getInstance()->normalize($customer, [
            AbstractNormalizer::GROUPS => ['update']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'json' => $data,
            'uriParameters' => [$customer->getUid()]
        ]);
    }

    /**
     * @param Customer $customer
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function deleteCustomer(Customer $customer, Token $jwt): Response
    {
        if ($customer->getUid() === null) {
            throw new DataServiceException('Customer lacks a uid', DataServiceException::MISSING_CUSTOMER_UID);
        }

        $endpoint = new Endpoint('/data/customers/%s', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParamters' => [$customer->getUid()]
        ]);
    }
}
