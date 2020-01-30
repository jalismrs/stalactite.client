<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\AuthToken;

use Jalismrs\Stalactite\Client\Data\AuthToken\Service;
use Jalismrs\Stalactite\Client\Tests\ServiceTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Data\AuthToken
 */
class ServiceTest extends
    TestCase
{
    use ServiceTestTrait;

    /**
     * testCustomer
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testCustomer(): void
    {
        $mockClient = new Service('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->customers();
        $client2 = $mockClient->customers();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }

    /**
     * testDomain
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testDomain(): void
    {
        $mockClient = new Service('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->domains();
        $client2 = $mockClient->domains();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }

    /**
     * testPost
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testPost(): void
    {
        $mockClient = new Service('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->posts();
        $client2 = $mockClient->posts();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }

    /**
     * testUser
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testUser(): void
    {
        $mockClient = new Service('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->users();
        $client2 = $mockClient->users();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }
}
