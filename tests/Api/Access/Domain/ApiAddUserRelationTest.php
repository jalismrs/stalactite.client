<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Access\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Access\Domain\Service;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiAddUserRelationTest
 *
 * @package Jalismrs\Stalactite\Service\Tests\Access\Domain
 */
class ApiAddUserRelationTest extends
    TestCase
{
    /**
     * testAddUserRelation
     *
     * @return void
     *
     * @throws ClientException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     * @throws RequestException
     */
    public function testAddUserRelation(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error' => null,
                        'relation' => Serializer::getInstance()
                            ->normalize(
                                ModelFactory::getTestableDomainUserRelation(),
                                [
                                    AbstractNormalizer::GROUPS => [
                                        'main',
                                    ],
                                ]
                            )
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->addUserRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
        static::assertTrue($response->isSuccess());
        static::assertNull($response->getError());
        static::assertInstanceOf(DomainUserRelation::class, $response->getData()['relation']);
    }
    
    /**
     * testThrowOnInvalidResponseAddUserRelation
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     * @throws RequestException
     */
    public function testThrowOnInvalidResponseAddUserRelation(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error' => null,
                        'relation' => []
                        // wrong type
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $mockService->addUserRelation(
            DataTestModelFactory::getTestableDomain(),
            DataTestModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }
}
