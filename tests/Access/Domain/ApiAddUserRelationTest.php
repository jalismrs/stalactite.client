<?php
declare(strict_types = 1);

namespace Test\Access\Domain;

use Jalismrs\Stalactite\Client\Access\Domain\Client;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelationModel;
use Jalismrs\Stalactite\Client\ClientException;
use Test\Access\ModelFactory;
use Test\Data\ModelFactory as DataTestModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiAddUserRelationTest
 *
 * @package Test\Access\Domain
 */
class ApiAddUserRelationTest extends
    TestCase
{
    /**
     * testAddUserRelation
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testAddUserRelation() : void
    {
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'  => true,
                                'error'    => null,
                                'relation' => ModelFactory::getTestableDomainUserRelation()
                                                          ->asArray()
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $response = $mockAPIClient->addUserRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
        static::assertTrue($response->isSuccess());
        static::assertNull($response->getError());
        static::assertInstanceOf(DomainUserRelationModel::class, $response->getData()['relation']);
    }
    
    /**
     * testThrowOnInvalidResponseAddUserRelation
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowOnInvalidResponseAddUserRelation() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'  => true,
                                'error'    => null,
                                'relation' => []
                                // wrong type
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $mockAPIClient->addUserRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }
}
