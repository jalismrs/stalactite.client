<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Service\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Tests\ServiceTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication
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
     * @throws RequestException
     */
    public function testTrustedApp(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $mockService1 = $mockService->trustedApps();
        $mockService2 = $mockService->trustedApps();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}
