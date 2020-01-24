<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Client;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Test
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;

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
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->access();
        $client2 = $baseClient->access();

        self::checkClients(
            $baseClient,
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
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->authentication();
        $client2 = $baseClient->authentication();

        self::checkClients(
            $baseClient,
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
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->data();
        $client2 = $baseClient->data();

        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
