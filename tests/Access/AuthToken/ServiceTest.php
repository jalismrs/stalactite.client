<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\AuthToken;

use Jalismrs\Stalactite\Client\Access\AuthToken\Service;
use Jalismrs\Stalactite\Client\Tests\ServiceTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Access\AuthToken
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
        
        $client1 = $mockService->customers();
        $client2 = $mockService->customers();
        
        self::checkClients(
            $mockService,
            $client1,
            $client2
        );
    }
    
    /**
     * testDomainDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testDomainDefault(): void
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
