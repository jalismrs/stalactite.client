<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api;

use Jalismrs\Stalactite\Client\Client;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\TestCase;

/**
 * ApiAbstract
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api
 */
abstract class ApiAbstract extends
    TestCase
{
    /**
     * createMockClient
     *
     * @return Client
     *
     * @throws RuntimeException
     */
    final protected function createMockClient() : Client
    {
        $mock = $this->createMock(Client::class);
        
        $mock
            ->expects(static::once())
            ->method('request');
        
        return $mock;
    }
    
    /**
     * testRequestMethodCalledOnce
     *
     * @return void
     */
    abstract public function testRequestMethodCalledOnce(): void;
}
