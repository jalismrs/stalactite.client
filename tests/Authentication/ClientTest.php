<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Client;
use Jalismrs\Stalactite\Test\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test
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
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientTrustedApp() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->clientTrustedApp();
        $client2 = $baseClient->clientTrustedApp();
    
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
