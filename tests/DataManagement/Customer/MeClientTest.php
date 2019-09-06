<?php

namespace jalismrs\Stalactite\Client\Test\Customer;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Customer\MeClient;
use jalismrs\Stalactite\Client\DataManagement\Model\Customer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class MeClientTest extends TestCase
{
    /**
     * @return Customer
     */
    private static function getTestableCustomer(): Customer
    {
        $customer = new Customer();
        $customer->setEmail('goodmorning@hello.hi')
            ->setFirstName('azerty')
            ->setLastName('uiop')
            ->setGoogleId('0123456789')
            ->setUid('azertyuiop');

        return $customer;
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGet(): void
    {
        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'me' => self::getTestableCustomer()->asArray()
            ]))
        ]);

        $mockAPIClient = new MeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $this->assertIsArray($mockAPIClient->get('fake user jwt'));
    }

    /**
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnInvalidResponseGet(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);

        $mockHttpClient = new MockHttpClient([
            new MockResponse(json_encode([
                'success' => true,
                'error' => null,
                'me' => [] // invalid customer
            ]))
        ]);

        $mockAPIClient = new MeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $mockAPIClient->get('fake user jwt');
    }
}