<?php
declare(strict_types=1);

namespace Test\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Client;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Test\ClientTestTrait;

/**
 * ClientTest
 *
 * @package Test\Authentication
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
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->trustedApps();
        $client2 = $baseClient->trustedApps();

        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
