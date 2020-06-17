<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Customer\Me;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;

/**
 * Class Service
 * @package Jalismrs\Stalactite\Client\Data\Customer\Me
 */
class Service extends AbstractService
{
    /**
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getMe(Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/customers/me');
        $endpoint->setResponseValidationSchema(new JsonSchema(Customer::getSchema()))
            ->setResponseFormatter(static fn(array $response): Customer => ModelFactory::createCustomer($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt
        ]);
    }
}
