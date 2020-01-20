<?php
declare(strict_types = 1);

namespace Test\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Client;
use Test\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Test\Authentication
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testClientTrustedApp
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientTrustedApp() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->trustedApp();
        $client2 = $baseClient->trustedApp();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
