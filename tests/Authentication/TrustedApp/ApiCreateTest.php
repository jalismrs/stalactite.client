<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\TrustedApp;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Client;
use Jalismrs\Stalactite\Client\ClientException;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Jalismrs\Stalactite\Client\Tests\Authentication\ModelFactory;

/**
 * ApiCreateTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Authentication\TrustedApp
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
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testCreate(): void
    {
        $trustedAppModel = ModelFactory::getTestableTrustedApp();
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success' => true,
                            'error' => null,
                            'trustedApp' => array_merge(
                                $trustedAppModel->asArray(),
                                [
                                    'resetToken' => $trustedAppModel->getResetToken()
                                ]
                            )
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );

        $mockClient = new Client(
            'http://fakeHost',
            null,
            $mockHttpClient
        );

        $response = $mockClient->createTrustedApp(
            ModelFactory::getTestableTrustedApp(),
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
     * testThrowOnCreate
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testThrowOnCreate(): void
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
                                'trustedApp' => []
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $mockClient->createTrustedApp(
            ModelFactory::getTestableTrustedApp(),
            'fake user jwt'
        );
    }
}
