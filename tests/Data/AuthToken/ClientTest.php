<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\AuthToken;

use Jalismrs\Stalactite\Client\Data\AuthToken\Client;
use Jalismrs\Stalactite\Client\Tests\ClientTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\AuthToken
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;

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
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );

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
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );

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
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );

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
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );

        $client1 = $mockClient->users();
        $client2 = $mockClient->users();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }
}
