<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\AuthToken;

use Jalismrs\Stalactite\Client\Data\AuthToken\Service;
use Jalismrs\Stalactite\Client\Tests\ServiceTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Data\AuthToken
 */
class ServiceTest extends
    TestCase
{
    use ServiceTestTrait;

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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $mockService1 = $mockService->customers();
        $mockService2 = $mockService->customers();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $mockService1 = $mockService->domains();
        $mockService2 = $mockService->domains();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $mockService1 = $mockService->posts();
        $mockService2 = $mockService->posts();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $mockService1 = $mockService->users();
        $mockService2 = $mockService->users();

        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}
