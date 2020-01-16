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
 * @package Jalismrs\Stalactite\Test\Authentication
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
        
        $client1 = $baseClient->getClientTrustedApp();
        $client2 = $baseClient->getClientTrustedApp();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
