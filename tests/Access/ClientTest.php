<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access;

use Jalismrs\Stalactite\Client\Access\Client;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Jalismrs\Stalactite\Client\Tests\ClientTestTrait;

/**
 * ClientTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Access
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;

    /**
     * testAuthToken
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testAuthToken(): void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->authToken();
        $client2 = $baseClient->authToken();

        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }

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
     * testRelation
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testRelation(): void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->relations();
        $client2 = $baseClient->relations();

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
