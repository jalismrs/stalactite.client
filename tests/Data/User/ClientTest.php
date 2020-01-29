<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\User;

use Jalismrs\Stalactite\Client\Data\User\Client;
use Jalismrs\Stalactite\Client\Tests\ClientTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Data\User
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;

    /**
     * testLead
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testLead(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->leads();
        $client2 = $mockClient->leads();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }

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
        $mockClient = new Client('http://fakeHost');
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
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->posts();
        $client2 = $mockClient->posts();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }
}
