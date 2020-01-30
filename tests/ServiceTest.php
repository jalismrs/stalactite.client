<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Service;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @package Test
 */
class ServiceTest extends
    TestCase
{
    use ServiceTestTrait;

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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $mockService1 = $mockService->access();
        $mockService2 = $mockService->access();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $mockService1 = $mockService->authentication();
        $mockService2 = $mockService->authentication();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $mockService1 = $mockService->data();
        $mockService2 = $mockService->data();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}
