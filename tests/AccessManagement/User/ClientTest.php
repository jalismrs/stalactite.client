<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement\User;

use Jalismrs\Stalactite\Client\AccessManagement\User\Client;
use Jalismrs\Stalactite\Test\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement\User
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testClientMe
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testClientMe() : void
    {
        $baseClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->getClientMe();
        $client2 = $baseClient->getClientMe();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
