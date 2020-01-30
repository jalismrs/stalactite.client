<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Service;
use Jalismrs\Stalactite\Client\Tests\ServiceTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @packageJalismrs\Stalactite\Service\Tests\Authentication
 */
class ServiceTest extends
    TestCase
{
    use ServiceTestTrait;

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
        $mockClient = new Service('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->trustedApps();
        $client2 = $mockClient->trustedApps();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }
}
