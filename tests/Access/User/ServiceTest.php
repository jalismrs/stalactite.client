<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\User;

use Jalismrs\Stalactite\Client\Access\User\Service;
use Jalismrs\Stalactite\Client\Tests\ServiceTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @packageJalismrs\Stalactite\Service\Tests\Access\User
 */
class ServiceTest extends
    TestCase
{
    use ServiceTestTrait;

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
        $mockClient = new Service('http://fakeHost');
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
}
