<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Access\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\Access\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Access\User\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory as DataTestModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetAccessClearanceTest
 *
 * @packageJalismrs\Stalactite\Client\Tests\Access\User
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
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws CircularReferenceException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function testGetAccessClearance(): void
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
                                'clearance' => $serializer->normalize(
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

        $response = $mockAPIClient->getAccessClearance(
            DataTestModelFactory::getTestableUser(), DataTestModelFactory::getTestableDomain(),
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
    public function testThrowExceptionOnInvalidResponseGetAccessClearance(): void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);

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
                                'clearance' => []
                                // wrong type
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );

        $mockAPIClient->getAccessClearance(
            DataTestModelFactory::getTestableUser(), DataTestModelFactory::getTestableDomain(),
            'fake user jwt'
        );
    }
}
