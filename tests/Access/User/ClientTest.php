<?php
declare(strict_types=1);

namespace Test\Access\User;

use Jalismrs\Stalactite\Client\Access\User\Client;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Test\ClientTestTrait;

/**
 * ClientTest
 *
 * @package Test\Access\User
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
}
