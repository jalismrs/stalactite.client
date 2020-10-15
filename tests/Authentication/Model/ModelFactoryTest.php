<?php

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Model;

use Jalismrs\Stalactite\Client\Authentication\Model\ModelFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class ModelFactoryTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\Model
 *
 * @covers \Jalismrs\Stalactite\Client\Authentication\Model\ModelFactory
 */
class ModelFactoryTest extends
    TestCase
{
    /**
     * testCreateClientApp
     *
     * @return void
     */
    public function testCreateClientApp() : void
    {
        // arrange
        $data = [];
        
        // act
        $output = ModelFactory::createClientApp($data);
        
        // assert
        self::assertNull(
            $output->getGoogleOAuthClientId()
        );
        self::assertNull(
            $output->getName()
        );
    }
    
    /**
     * testCreateServerApp
     *
     * @return void
     */
    public function testCreateServerApp() : void
    {
        // arrange
        $data = [];
        
        // act
        $output = ModelFactory::createServerApp($data);
        
        // assert
        self::assertNull(
            $output->getName()
        );
        self::assertNull(
            $output->getTokenSignatureKey()
        );
    }
}
