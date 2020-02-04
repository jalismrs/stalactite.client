<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Access\Customer\Service;
use Jalismrs\Stalactite\Client\Access\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetAccessClearanceTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Access\Customer
 */
class ApiGetAccessClearanceTest extends
    TestCase
{
    /**
     * testGetAccessClearance
     *
     * @return void
     *
     * @throws ClientException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGetAccessClearance() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'   => true,
                                'error'     => null,
                                'clearance' => $mockClient
                                    ->getSerializer()
                                    ->normalize(
                                        ModelFactory::getTestableAccessClearance(),
                                        [
                                            AbstractNormalizer::GROUPS => [
                                                'main',
                                            ],
                                        ]
                                    ),
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $response = $mockService->getAccessClearance(
            DataTestModelFactory::getTestableCustomer(),
            DataTestModelFactory::getTestableDomain(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            AccessClearance::class,
            $response->getData()['clearance']
        );
    }
    
    /**
     * testThrowExceptionOnInvalidResponseGetAccessClearance
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetAccessClearance() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'   => true,
                                'error'     => null,
                                'clearance' => []
                                // wrong type
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $mockService->getAccessClearance(
            DataTestModelFactory::getTestableCustomer(),
            DataTestModelFactory::getTestableDomain(),
            'fake user jwt'
        );
    }
}
