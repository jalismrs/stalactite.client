<?php

namespace jalismrs\Stalactite\Client\Test\DataManagement\Customer;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Customer\MeClient;
use jalismrs\Stalactite\Client\DataManagement\Model\Customer;
use jalismrs\Stalactite\Client\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class MeClientTest extends TestCase
{
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
                'me' => ModelFactory::getTestableCustomer()->asArray()
            ]))
        ]);

        $mockAPIClient = new MeClient('http://fakeClient');
        $mockAPIClient->setHttpClient($mockHttpClient);

        $response = $mockAPIClient->get('fake user jwt');
        $this->assertTrue($response->success());
        $this->assertNull($response->getError());
        $this->assertInstanceOf(Customer::class, $response->getData()['me']);
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