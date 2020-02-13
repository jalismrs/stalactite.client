<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Service\Access\AuthToken;

use Jalismrs\Stalactite\Client\Access\AuthToken\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Tests\ServiceTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Service\Access\AuthToken
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
    public function testCustomer() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');
        
        $mockService1 = $mockService->customers();
        $mockService2 = $mockService->customers();
        
        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
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
    public function testDomainDefault() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');
        
        $mockService1 = $mockService->domains();
        $mockService2 = $mockService->domains();
        
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
    public function testUser() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');
        
        $mockService1 = $mockService->users();
        $mockService2 = $mockService->users();
        
        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}
