<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Client;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTestEndpoint
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
abstract class AbstractTestEndpoint extends
    TestCase
{
    /**
     * testRequestMethodCalledOnce
     *
     * @return void
     */
    abstract public function testRequestMethodCalledOnce() : void;
    
    /**
     * createMockClient
     *
     * @param bool $requestCalled
     *
     * @return Client
     */
    final protected function createMockClient(
        bool $requestCalled = true
    ) : Client {
        $mockClient = $this->createMock(Client::class);
        
        $mockClient
            ->expects(
                $requestCalled
                    ? self::once()
                    : self::never()
            )
            ->method('request');
        
        return $mockClient;
    }
}
