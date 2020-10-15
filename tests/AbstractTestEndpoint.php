<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\Client;
use PHPUnit\Framework\TestCase;

/**
 * ApiAbstract
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
     * @return \Jalismrs\Stalactite\Client\Client
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
