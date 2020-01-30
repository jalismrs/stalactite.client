<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Service;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @package Test
 */
class ServiceTest extends
    TestCase
{
    use ServiceTestTrait;

    /**
     * testAccess
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testAccess(): void
    {
        $mockClient = new Service('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->access();
        $client2 = $mockClient->access();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }

    /**
     * testAuthentication
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testAuthentication(): void
    {
        $mockClient = new Service('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->authentication();
        $client2 = $mockClient->authentication();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }

    /**
     * testData
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testData(): void
    {
        $mockClient = new Service('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->data();
        $client2 = $mockClient->data();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }
}
