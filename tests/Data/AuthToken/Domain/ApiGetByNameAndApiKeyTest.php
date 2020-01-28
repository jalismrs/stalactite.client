<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\AuthToken\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\AuthToken\Domain\Client;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetByNameAndApiKeyTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\AuthToken\Domain
 */
class ApiGetByNameAndApiKeyTest extends
    TestCase
{
    /**
     * testGetByNameAndApiKey
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     * @throws InvalidArgumentException
     */
    public function testGetByNameAndApiKey(): void
    {
        $serializer = Serializer::getInstance();

        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null,
                                'domains' => [
                                    $serializer->normalize(
                                        ModelFactory::getTestableDomain(),
                                        [
                                            AbstractNormalizer::GROUPS => [
                                                'main',
                                            ],
                                        ]
                                    )
                                ]
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $domainModel = ModelFactory::getTestableDomain();

        $response = $mockAPIClient->getByNameAndApiKey(
            $domainModel->getName(),
            $domainModel->getApiKey(),
            'fake API auth token'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            Domain::class,
            $response->getData()['domains']
        );
    }

    /**
     * testThrowOnInvalidResponseGetByNameAndApiKey
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function testThrowOnInvalidResponseGetByNameAndApiKey(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $serializer = Serializer::getInstance();

        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null,
                                'domains' => $serializer->normalize(
                                    ModelFactory::getTestableDomain(),
                                    [
                                        AbstractNormalizer::GROUPS => [
                                            'main',
                                        ],
                                    ]
                                )
                                // invalid type
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $domainModel = ModelFactory::getTestableDomain();

        $mockAPIClient->getByNameAndApiKey(
            $domainModel->getName(),
            $domainModel->getApiKey(),
            'fake API auth token'
        );
    }
}
