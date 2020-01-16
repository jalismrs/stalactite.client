<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\DataManagement\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Customer\Client;
use Jalismrs\Stalactite\Client\DataManagement\Model\CustomerModel;
use Jalismrs\Stalactite\Test\ClientTestTrait;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiGetTest
 *
 * @package Jalismrs\Stalactite\Test\DataManagement\Customer
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
