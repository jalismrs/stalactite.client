<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\User;

use Jalismrs\Stalactite\Client\Access\User\Client;
use Jalismrs\Stalactite\Client\Tests\ClientTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Access\User
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;

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

        $client1 = $mockClient->me();
        $client2 = $mockClient->me();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }
}
