<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\DataManagement\User\PhoneLine;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\PhoneLineModel;
use Jalismrs\Stalactite\Client\DataManagement\User\PhoneLine\Client;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiGetAllTest
 *
 * @package Jalismrs\Stalactite\Test\DataManagement\User\Post
 */
class ApiGetAllTest extends
    TestCase
{
    /**
     * testGetAll
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testGetAll() : void
    {
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'    => true,
                            'error'      => null,
                            'phoneLines' => [
                                ModelFactory::getTestablePhoneLine()
                                            ->asArray()
                            ]
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            $mockHttpClient
        );
        
        $response = $mockAPIClient->getAll(
            ModelFactory::getTestableUser(),
            'fake user jwt'
        );
        self::assertTrue($response->success());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            PhoneLineModel::class,
            $response->getData()['phoneLines']
        );
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetAll() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success'    => true,
                            'error'      => null,
                            'phoneLines' => ModelFactory::getTestablePhoneLine()
                                                        ->asArray()
                            // invalid type
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            $mockHttpClient
        );
        
        $mockAPIClient->getAll(
            ModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }
}
