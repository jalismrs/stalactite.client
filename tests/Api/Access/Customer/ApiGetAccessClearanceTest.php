<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\Customer;

use Jalismrs\Stalactite\Client\Access\Customer\Service;
use Jalismrs\Stalactite\Client\Access\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetAccessClearanceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Access\Customer
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
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testGetAccessClearance() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success'   => true,
                        'error'     => null,
                        'clearance' => Serializer::getInstance()
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testThrowExceptionOnInvalidResponseGetAccessClearance() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
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
        );
        
        $mockService->getAccessClearance(
            DataTestModelFactory::getTestableCustomer(),
            DataTestModelFactory::getTestableDomain(),
            'fake user jwt'
        );
    }
}
