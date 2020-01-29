<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\AuthToken;

use Jalismrs\Stalactite\Client\Access\AuthToken\Client;
use Jalismrs\Stalactite\Client\Tests\ClientTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Access\AuthToken
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testCustomer
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testCustomer(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');
        
        $client1 = $mockClient->customers();
        $client2 = $mockClient->customers();
        
        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testDomainDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testDomainDefault(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->domains();
        $client2 = $mockClient->domains();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
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
    public function testUser(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockClient->setHttpClient(
            new MockHttpClient()
        );
        $mockClient->setLogger(
            new TestLogger()
        );
        $mockClient->setUserAgent('fake user agent');

        $client1 = $mockClient->users();
        $client2 = $mockClient->users();

        self::checkClients(
            $mockClient,
            $client1,
            $client2
        );
    }
}
