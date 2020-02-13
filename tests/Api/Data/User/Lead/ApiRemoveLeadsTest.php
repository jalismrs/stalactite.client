<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\User\Lead;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Data\User\Lead\Service;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiRemoveLeadsTest
 * @package Jalismrs\Stalactite\Service\Tests\Data\User\Lead
 */
class ApiRemoveLeadsTest extends
    TestCase
{
    /**
     * testRemoveLeads
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws RequestException
     * @throws SerializerException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRemoveLeads(): void
    {
        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error' => null
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $response = $mockService->removeLeads(
            ModelFactory::getTestableUser(),
            [
                ModelFactory::getTestablePost()
            ],
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
    }
    
    /**
     * testThrowOnInvalidResponseRemoveLeads
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws RequestException
     * @throws SerializerException
     */
    public function testThrowOnInvalidResponseRemoveLeads(): void
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
                        'error' => false
                        // invalid type
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $mockService->removeLeads(
            ModelFactory::getTestableUser(),
            [
                ModelFactory::getTestablePost()
            ],
            'fake user jwt'
        );
    }
    
    /**
     * testThrowOnInvalidPostsParameterRemoveLeads
     *
     * @return void
     *
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws RequestException
     * @throws SerializerException
     */
    public function testThrowOnInvalidPostsParameterRemoveLeads(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $mockClient = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error' => null
                    ],
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $mockService->removeLeads(
            ModelFactory::getTestableUser(),
            [
                'not a lead'
            ],
            'fake user jwt'
        );
    }
}
