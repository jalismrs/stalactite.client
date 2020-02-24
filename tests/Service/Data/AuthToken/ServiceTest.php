<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Service\Data\AuthToken;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\AuthToken\Service;
use Jalismrs\Stalactite\Client\Tests\Service\ServiceAbstract;
use PHPUnit\Framework\ExpectationFailedException;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ServiceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Service\Data\AuthToken
 */
class ServiceTest extends
    ServiceAbstract
{
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
     * testDomain
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testDomain() : void
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
     * testPost
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testPost() : void
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
