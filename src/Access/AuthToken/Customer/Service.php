<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\Customer;

use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken\Customer
 */
class Service extends AbstractService
{
    /**
     * @param Customer $customer
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function deleteRelationsByCustomer(Customer $customer, string $apiAuthToken): Response
    {
        if ($customer->getUid() === null) {
            throw new AccessServiceException('Customer lacks a uid', AccessServiceException::MISSING_CUSTOMER_UID);
        }

        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/access/auth-token/customers/%s/relations', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$customer->getUid()]
        ]);
    }
}
