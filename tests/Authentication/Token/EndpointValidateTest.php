<?php

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Token;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Lcobucci\JWT\Configuration;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class EndpointValidateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\Token
 *
 * @covers \Jalismrs\Stalactite\Client\Authentication\Token\Service
 */
class EndpointValidateTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $config = Configuration::forUnsecuredSigner();

        $mockToken = $config->builder()->relatedTo('test-user')->getToken($config->signer(), $config->signingKey());
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);

        $systemUnderTest->validate($mockToken);
    }
}
