<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Customer;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Normalizer;
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
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getAllCustomers(string $jwt): Response
    {
        $endpoint = new Endpoint('/data/customers');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::CUSTOMER, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn(array $customer): Customer => ModelFactory::createCustomer($customer),
                    $response
                );
            });
        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt
        ]);
    }

    /**
     * @param string $uid
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getCustomer(string $uid, string $jwt): Response
    {
        $endpoint = new Endpoint('/data/customers/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::CUSTOMER))
            ->setResponseFormatter(static fn(array $response): Customer => ModelFactory::createCustomer($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param Customer $customer
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function createCustomer(Customer $customer, string $jwt): Response
    {
        $endpoint = new Endpoint('/data/customers', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::CUSTOMER))
            ->setResponseFormatter(static fn(array $response): Customer => ModelFactory::createCustomer($response));

        $data = Normalizer::getInstance()->normalize($customer, [
            AbstractNormalizer::GROUPS => ['create']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => $data
        ]);
    }

    /**
     * @param Customer $customer
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function updateCustomer(Customer $customer, string $jwt): Response
    {
        if ($customer->getUid() === null) {
            throw new DataServiceException('Customer lacks a uid', DataServiceException::MISSING_CUSTOMER_UID);
        }

        $endpoint = new Endpoint('/data/customers/%s', 'PUT');

        $data = Normalizer::getInstance()->normalize($customer, [
            AbstractNormalizer::GROUPS => ['update']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => $data,
            'uriParameters' => [$customer->getUid()]
        ]);
    }

    /**
     * @param Customer $customer
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function deleteCustomer(Customer $customer, string $jwt): Response
    {
        if ($customer->getUid() === null) {
            throw new DataServiceException('Customer lacks a uid', DataServiceException::MISSING_CUSTOMER_UID);
        }

        $endpoint = new Endpoint('/data/customers/%s', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParamters' => [$customer->getUid()]
        ]);
    }
}
