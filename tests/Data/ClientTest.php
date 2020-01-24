<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data;

use Jalismrs\Stalactite\Client\Data\Client;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Jalismrs\Stalactite\Client\Tests\ClientTestTrait;

/**
 * ClientTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Data
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

        $client1 = $baseClient->authTokens();
        $client2 = $baseClient->authTokens();

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
     * testPhoneType
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testPhoneType(): void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->phoneTypes();
        $client2 = $baseClient->phoneTypes();

        self::checkClients(
            $baseClient,
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
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->posts();
        $client2 = $baseClient->posts();

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
