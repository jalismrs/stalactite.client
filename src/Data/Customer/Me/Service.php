<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Customer\Me;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Class Service
 * @package Jalismrs\Stalactite\Client\Data\Customer\Me
 */
class Service extends AbstractService
{
    /**
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getMe(string $jwt): Response
    {
        $endpoint = new Endpoint('/data/customers/me');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::CUSTOMER))
            ->setResponseFormatter(static fn(array $response): Customer => ModelFactory::createCustomer($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt
        ]);
    }
}
