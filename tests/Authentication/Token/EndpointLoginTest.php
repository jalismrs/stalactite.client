<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Token;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Authentication\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use JsonException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class EndpointLoginTest
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\Token
 * @covers \Jalismrs\Stalactite\Client\Authentication\Token\Service
 */
class EndpointLoginTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;

    /**
     * @throws JsonException
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testLogin(): void
    {
        $config = Configuration::forUnsecuredSigner();
        $mockToken = $config->builder()->relatedTo('test-user')
            ->getToken($config->signer(), $config->signingKey());

        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    ['token' => $mockToken->toString()],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $systemUnderTest = $this->createSystemUnderTest($testClient);

        // assert valid return and response content
        $response = $systemUnderTest->login(
            TestableModelFactory::getTestableClientApp(),
            'fakeUserGoogleToken'
        );

        self::assertArrayHasKey(
            'token',
            $response->getBody()
        );
        self::assertInstanceOf(
            Token::class,
            $response->getBody()['token']
        );
    }

    /**
     * @throws JsonException
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testThrowOnInvalidTokenReceived(): void
    {
        $this->expectException(AuthenticationServiceException::class);
        $this->expectExceptionCode(AuthenticationServiceException::INVALID_TOKEN);

        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    ['token' => 'yolo'],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $systemUnderTest = $this->createSystemUnderTest($testClient);

        // assert valid return and response content
        $systemUnderTest->login(
            TestableModelFactory::getTestableClientApp(),
            'fakeUserGoogleToken'
        );
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);

        $systemUnderTest->login(
            TestableModelFactory::getTestableClientApp(),
            'fakeUserGoogleToken'
        );
    }
}
