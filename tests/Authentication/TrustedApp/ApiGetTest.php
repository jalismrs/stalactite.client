<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\TrustedApp;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Tests\Authentication\ModelFactory;
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
 * ApiGetTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Authentication\TrustedApp
 */
class ApiGetTest extends
    TestCase
{
    /**
     * testGet
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
    public function testGet(): void
    {
        $serializer = Serializer::getInstance();

        $mockClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null,
                                'trustedApp' => $serializer->normalize(
                                    ModelFactory::getTestableTrustedApp(),
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

        $response = $mockClient->getTrustedApp(
            ModelFactory::getTestableTrustedApp()
                ->getUid(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            TrustedApp::class,
            $response->getData()['trustedApp']
        );
    }

    /**
     * testInvalidResponseOnGet
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testInvalidResponseOnGet(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

        $mockClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error' => null,
                                'trustedApps' => 'wrong response type'
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $mockClient->getTrustedApp(
            ModelFactory::getTestableTrustedApp()
                ->getUid(),
            'fake user jwt'
        );
    }
}
