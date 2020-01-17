<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement\Relation;

use Jalismrs\Stalactite\Client\AccessManagement\Relation\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Test\AccessManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiDeleteRelationTest
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement\Relation
 */
class ApiDeleteRelationTest extends
    TestCase
{
    /**
     * testDeleteRelation
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
    public function testDeleteRelation() : void
    {
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient(
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
            )
        );
        
        $response = $mockAPIClient->deleteRelation(
            ModelFactory::getTestableDomainUserRelation(),
            'fake user jwt'
        );
        static::assertTrue($response->isSuccess());
        static::assertNull($response->getError());
    }
    
    /**
     * testThrowExceptionOnInvalidResponseDeleteRelation
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseDeleteRelation() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient(
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
            )
        );
        
        $mockAPIClient->deleteRelation(
            ModelFactory::getTestableDomainUserRelation(),
            'fake user jwt'
        );
    }
}
