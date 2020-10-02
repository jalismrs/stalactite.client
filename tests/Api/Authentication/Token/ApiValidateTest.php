<?php

namespace Jalismrs\Stalactite\Client\Tests\Api\Authentication\Token;

use Jalismrs\Stalactite\Client\Authentication\Token\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Tests\Api\EndpointTest;
use Lcobucci\JWT\Builder;
use Psr\SimpleCache\InvalidArgumentException;

class ApiValidateTest extends EndpointTest
{
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockToken = (new Builder())->relatedTo('test-user')->getToken();
        $mockService = new Service($this->createMockClient());
        $mockService->validate($mockToken);
    }
}