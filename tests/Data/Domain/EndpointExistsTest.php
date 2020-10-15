<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Domain;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Data\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class EndpointExistsTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Domain
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Domain\Service
 */
class EndpointExistsTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockClient      = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);
        
        $systemUnderTest->exists(
            TestableModelFactory::getTestableDomain()
                        ->getUid(),
            JwtFactory::create()
        );
    }
}
