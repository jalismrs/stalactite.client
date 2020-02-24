<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Service\Data\Customer;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\Customer\Service;
use Jalismrs\Stalactite\Client\Tests\Service\ServiceAbstract;
use PHPUnit\Framework\ExpectationFailedException;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Service\Data\Customer
 */
class ServiceTest extends
    ServiceAbstract
{
    /**
     * testMe
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testMe() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');
        
        $mockService1 = $mockService->me();
        $mockService2 = $mockService->me();
        
        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}
