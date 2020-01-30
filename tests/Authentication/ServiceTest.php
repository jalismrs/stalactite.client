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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $client1 = $mockService->trustedApps();
        $client2 = $mockService->trustedApps();

        self::checkClients(
            $mockService,
            $client1,
            $client2
        );
    }
}
