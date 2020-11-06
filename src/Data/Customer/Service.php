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
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data\Customer
 */
class Service extends
    AbstractService
{
    private ?Me\Service       $serviceMe = null;
    private ?Access\Service   $serviceAccess = null;
    private ?Relation\Service $serviceRelation = null;

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

    public function access(): Access\Service
    {
        if ($this->serviceAccess === null) {
            $this->serviceAccess = new Access\Service($this->getClient());
        }

        return $this->serviceAccess;
    }

    public function relations(): Relation\Service
    {
        if ($this->serviceRelation === null) {
            $this->serviceRelation = new Relation\Service($this->getClient());
        }

        return $this->serviceRelation;
    }

    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function all(Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/customers');
        $endpoint->setResponseValidationSchema(
            new JsonSchema(
                Customer::getSchema(),
                JsonSchema::LIST_TYPE
            )
        )
            ->setResponseFormatter(
                static function (array $response): array {
                    return array_map(
                        static fn(array $customer): Customer => ModelFactory::createCustomer($customer),
                        $response
                    );
                }
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                ]
            );
    }

    /**
     * @param string $uid
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function get(
        string $uid,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint('/data/customers/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Customer::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): Customer => ModelFactory::createCustomer($response)
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'uriParameters' => [$uid],
                ]
            );
    }

    /**
     * @param string $uid
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function exists(
        string $uid,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint(
            '/data/customers/%s',
            'HEAD'
        );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'uriParameters' => [$uid],
                ]
            );
    }

    /**
     * @param string $email
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function getByEmail(
        string $email,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint('/data/customers');
        $endpoint->setResponseValidationSchema(new JsonSchema(Customer::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): Customer => ModelFactory::createCustomer($response)
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'query' => ['email' => $email],
                ]
            );
    }

    /**
     * @param string $email
     * @param string $googleId
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function getByEmailAndGoogleId(
        string $email,
        string $googleId,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint('/data/customers');
        $endpoint->setResponseValidationSchema(new JsonSchema(Customer::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): Customer => ModelFactory::createCustomer($response)
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'query' => [
                        'email' => $email,
                        'googleId' => $googleId,
                    ],
                ]
            );
    }

    /**
     * @param Customer $customer
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function create(
        Customer $customer,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint(
            '/data/customers',
            'POST'
        );
        $endpoint->setResponseValidationSchema(new JsonSchema(Customer::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): Customer => ModelFactory::createCustomer($response)
            );

        $data = Normalizer::getInstance()
            ->normalize(
                $customer,
                [
                    AbstractNormalizer::GROUPS => ['create'],
                ]
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'json' => $data,
                ]
            );
    }

    /**
     * @param Customer $customer
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function update(
        Customer $customer,
        Token $jwt
    ): Response
    {
        if ($customer->getUid() === null) {
            throw new DataServiceException(
                'Customer lacks a uid',
                DataServiceException::MISSING_CUSTOMER_UID
            );
        }

        $endpoint = new Endpoint(
            '/data/customers/%s',
            'PUT'
        );

        $data = Normalizer::getInstance()
            ->normalize(
                $customer,
                [
                    AbstractNormalizer::GROUPS => ['update'],
                ]
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'json' => $data,
                    'uriParameters' => [$customer->getUid()],
                ]
            );
    }

    /**
     * @param Customer $customer
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function delete(
        Customer $customer,
        Token $jwt
    ): Response
    {
        if ($customer->getUid() === null) {
            throw new DataServiceException(
                'Customer lacks a uid',
                DataServiceException::MISSING_CUSTOMER_UID
            );
        }

        $endpoint = new Endpoint(
            '/data/customers/%s',
            'DELETE'
        );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'uriParamters' => [$customer->getUid()],
                ]
            );
    }
}
