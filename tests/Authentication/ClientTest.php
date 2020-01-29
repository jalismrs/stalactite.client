<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Client;
use Jalismrs\Stalactite\Client\Tests\ClientTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Authentication
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;

    /**
     * testTrustedApp
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testTrustedApp(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );

        $client1 = $mockClient->trustedApps();
        $client2 = $mockClient->trustedApps();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }
}
