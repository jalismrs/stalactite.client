<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Token;

use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use JsonException;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class ApiLoginTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication
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
    public function testLogin() : void
    {
        $mockToken = (new Builder())->relatedTo('test-user')
                                    ->getToken();
        
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    ['token' => (string)$mockToken],
                    JSON_THROW_ON_ERROR
                )
            )
        );
        
        $systemUnderTest = $this->createSystemUnderTest($testClient);
        
        // assert valid return and response content
        $response = $systemUnderTest->login(
            ModelFactory::getTestableClientApp(),
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
    public function testThrowOnInvalidTokenReceived() : void
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
            ModelFactory::getTestableClientApp(),
            'fakeUserGoogleToken'
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce() : void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);
        
        $systemUnderTest->login(
            ModelFactory::getTestableClientApp(),
            'fakeUserGoogleToken'
        );
    }
}
