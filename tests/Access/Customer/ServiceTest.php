<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Customer;

use Jalismrs\Stalactite\Client\Access\Customer\Service;
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
     */
    public function testMe(): void
    {
        $mockClient = new Service('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->me();
        $client2 = $mockClient->me();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }
}
