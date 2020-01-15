<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AccessManagement\RelationClient;
use Jalismrs\Stalactite\Client\ClientException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * RelationClientTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement
 */
class RelationClientTest extends
    TestCase
{
    /**
     * testDeleteRelation
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testDeleteRelation() : void
    {
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success' => true,
                            'error'   => null
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new RelationClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $response = $mockAPIClient->deleteRelation(
            ModelFactory::getTestableDomainUserRelation(),
            'fake user jwt'
        );
        static::assertTrue($response->success());
        static::assertNull($response->getError());
    }
    
    /**
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseDeleteRelation() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success' => true,
                            'error'   => false
                            // wrong type
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new RelationClient(
            'http://fakeClient',
            null,
            $mockHttpClient
        );
        
        $mockAPIClient->deleteRelation(
            ModelFactory::getTestableDomainUserRelation(),
            'fake user jwt'
        );
    }
}
