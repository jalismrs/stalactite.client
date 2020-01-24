<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\AuthToken;

use Jalismrs\Stalactite\Client\Access\AuthToken\Client;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Jalismrs\Stalactite\Client\Tests\ClientTestTrait;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Access\AuthToken
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
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->customers();
        $client2 = $baseClient->customers();

        self::checkClients(
            $baseClient,
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
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->domains();
        $client2 = $baseClient->domains();

        self::checkClients(
            $baseClient,
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
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->users();
        $client2 = $baseClient->users();

        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
