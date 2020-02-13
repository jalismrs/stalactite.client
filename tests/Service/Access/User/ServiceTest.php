<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Service\Access\User;

use Jalismrs\Stalactite\Client\Access\User\Service;
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
 * @package Jalismrs\Stalactite\Client\Tests\Service\Access\User
 */
class ServiceTest extends
    TestCase
{
    use ServiceTestTrait;
    
    /**
     * testMe
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testMe() : void
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
        
        $mockService1 = $mockService->me();
        $mockService2 = $mockService->me();
        
        self::checkServices(
            $mockService,
            $mockService1,
            $mockService2
        );
    }
}
