<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Service;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Service;
use Jalismrs\Stalactite\Client\Tests\ServiceTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Service
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
    public function testAccess() : void
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
    public function testAuthentication() : void
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
    public function testData() : void
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
        
        $mockService1 = $mockService->data();
        $mockService2 = $mockService->data();
        
        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}
