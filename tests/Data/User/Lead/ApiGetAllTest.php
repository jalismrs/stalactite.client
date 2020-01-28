<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\User\Lead;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\User\Lead\Client;
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
 * ApiGetAllTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Data\User\Lead
 */
class ApiGetAllTest extends
    TestCase
{
    /**
     * testGetAll
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
    public function testGetAll(): void
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
                                'leads' => [
                                    $serializer->normalize(
                                        ModelFactory::getTestablePost(),
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

        $response = $mockAPIClient->getAllLeads(
            ModelFactory::getTestableUser(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            Post::class,
            $response->getData()['leads']
        );
    }

    /**
     * testThrowOnInvalidResponseGetAll
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function testThrowOnInvalidResponseGetAll(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

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
                                'leads' => $serializer->normalize(
                                    ModelFactory::getTestablePost(),
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

        $mockAPIClient->getAllLeads(
            ModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }
}
