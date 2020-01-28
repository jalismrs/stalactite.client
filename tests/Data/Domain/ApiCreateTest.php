<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Domain\Client;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiCreateTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Domain
 */
class ApiCreateTest extends
    TestCase
{
    /**
     * testCreate
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
    public function testCreate(): void
    {
        $serializer = Serializer::getInstance();

        $mockAPIClient = new Client('http://fakeHost');
        $mockAPIClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null,
                                'domain' => $serializer->normalize(
                                    ModelFactory::getTestableDomain(),
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
                ]
            )
        );

        $response = $mockAPIClient->createDomain(
            ModelFactory::getTestableDomain(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            Domain::class,
            $response->getData()['domain']
        );
    }

    /**
     * testThrowOnInvalidResponseCreate
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function testThrowOnInvalidResponseCreate(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockAPIClient = new Client('http://fakeHost');
        $mockAPIClient->setHttpClient(
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null,
                                'domain' => []
                                // invalid certification type
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $mockAPIClient->createDomain(
            ModelFactory::getTestableDomain(),
            'fake user jwt'
        );
    }
}
