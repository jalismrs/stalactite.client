<?php

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Token;

use Jalismrs\Stalactite\Client\Authentication\Token\Service;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Lcobucci\JWT\Builder;
use Psr\SimpleCache\InvalidArgumentException;

class EndpointValidateTest extends AbstractTestEndpoint
{
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockToken = (new Builder())->relatedTo('test-user')->getToken();
        $mockClient = $this->createMockClient();
        $testService = new Service($mockClient);
        
        $testService->validate($mockToken);
    }
}
