<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data;

use Jalismrs\Stalactite\Client\Data\Service;
use Jalismrs\Stalactite\Client\Tests\ServiceTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @packageJalismrs\Stalactite\Service\Tests\Data
 */
class ServiceTest extends
    TestCase
{
    use ServiceTestTrait;

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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $client1 = $mockService->authToken();
        $client2 = $mockService->authToken();

        self::checkClients(
            $mockService,
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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $client1 = $mockService->customers();
        $client2 = $mockService->customers();

        self::checkClients(
            $mockService,
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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $client1 = $mockService->domains();
        $client2 = $mockService->domains();

        self::checkClients(
            $mockService,
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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $client1 = $mockService->posts();
        $client2 = $mockService->posts();

        self::checkClients(
            $mockService,
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
        $mockService = new Service('http://fakeHost');
        $mockService->setHttpClient(
            new MockHttpClient()
        );
        $mockService->setLogger(
            new TestLogger()
        );
        $mockService->setUserAgent('fake user agent');

        $client1 = $mockService->users();
        $client2 = $mockService->users();

        self::checkClients(
            $mockService,
            $client1,
            $client2
        );
    }
}
