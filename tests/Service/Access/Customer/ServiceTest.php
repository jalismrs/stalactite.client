<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Service\Access\Customer;

use Jalismrs\Stalactite\Client\Access\Customer\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Tests\ServiceTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Access\Customer
 */
class ServiceTest extends
    TestCase
{
    use ServiceTestTrait;
    
    /**
     * testMe
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testMe(): void
    {
        $mockClient = new Client('http://fakeHost');
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
