<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\DataManagement\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Domain\Client;
use Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiGetByNameTest
 *
 * @package Jalismrs\Stalactite\Test\DataManagement\Domain
 */
class ApiGetByNameTest extends
    TestCase
{
    /**
     * testGetByName
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testGetByName() : void
    {
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success' => true,
                            'error'   => null,
                            'domains' => [
                                ModelFactory::getTestableDomain()
                                            ->asArray()
                            ]
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockAPIClient->getByName(
            ModelFactory::getTestableDomain()
                        ->getName(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            DomainModel::class,
            $response->getData()['domains']
        );
    }
    
    /**
     * testThrowOnInvalidResponseGetByName
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowOnInvalidResponseGetByName() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success' => true,
                            'error'   => null,
                            'domains' => ModelFactory::getTestableDomain()
                                                     ->asArray()
                            // invalid type
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockAPIClient->getByName(
            ModelFactory::getTestableDomain()
                        ->getName(),
            'fake user jwt'
        );
    }
}
