<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\User;

use Jalismrs\Stalactite\Client\Data\User\Client;
use Jalismrs\Stalactite\Client\Tests\ClientTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
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
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->leads();
        $client2 = $baseClient->leads();

        self::checkClients(
            $baseClient,
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
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->me();
        $client2 = $baseClient->me();

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
}
