<?php
declare(strict_types = 1);

namespace Test\Data\User;

use Jalismrs\Stalactite\Client\Data\User\Client;
use Test\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * ClientTest
 *
 * @package Test\Data\User
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;
    
    /**
     * testCertificationGraduation
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testCertificationGraduation() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->certificationGraduation();
        $client2 = $baseClient->certificationGraduation();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testLead
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testLead() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->lead();
        $client2 = $baseClient->lead();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testMe
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testMe() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->me();
        $client2 = $baseClient->me();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testPhoneLine
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPhoneLine() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->phoneLine();
        $client2 = $baseClient->phoneLine();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
    
    /**
     * testPost
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPost() : void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );
        
        $client1 = $baseClient->post();
        $client2 = $baseClient->post();
        
        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
