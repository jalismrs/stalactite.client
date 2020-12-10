<?php

namespace Jalismrs\Stalactite\Client\Data\Customer\Access;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data\Customer\Access
 */
class Service extends
    AbstractService
{
    /**
     * @param Customer $customer
     * @param Domain $domain
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function clearance(
        Customer $customer,
        Domain $domain,
        Token $jwt
    ): Response
    {
        if ($customer->getUid() === null) {
            throw new DataServiceException(
                'Customer lacks a uid',
                DataServiceException::MISSING_CUSTOMER_UID
            );
        }

        if ($domain->getUid() === null) {
            throw new DataServiceException(
                'Domain lacks a uid',
                DataServiceException::MISSING_DOMAIN_UID
            );
        }

        $endpoint = new Endpoint('/data/customers/%s/access/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(AccessClearance::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): AccessClearance => ModelFactory::createAccessClearance($response)
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'uriParameters' => [
                        $customer->getUid(),
                        $domain->getUid(),
                    ],
                ]
            );
    }
}
