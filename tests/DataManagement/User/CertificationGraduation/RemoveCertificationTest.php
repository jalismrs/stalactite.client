<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\DataManagement\User\CertificationGraduation;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\User\CertificationGraduation\Client;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ClientTest
 *
 * @package Jalismrs\Stalactite\Test\DataManagement\UserModel\CertificationGraduationModel
 */
class RemoveCertificationTest extends
    TestCase
{
    /**
     * testRemoveCertification
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public function testRemoveCertification() : void
    {
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success' => true,
                            'error'   => null
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            $mockHttpClient
        );
        
        $response = $mockAPIClient->removeCertification(
            ModelFactory::getTestableUser(),
            ModelFactory::getTestableCertificationGraduation(),
            'fake user jwt'
        );
        self::assertTrue($response->success());
        self::assertNull($response->getError());
    }
    
    /**
     * @throws ClientException
     * @throws InvalidSchemaException
     * @throws InvalidDataTypeException
     */
    public function testThrowExceptionOnInvalidResponseRemoveCertification() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockHttpClient = new MockHttpClient(
            [
                new MockResponse(
                    json_encode(
                        [
                            'success' => true,
                            'error'   => false
                            // invalid type
                        ],
                        JSON_THROW_ON_ERROR
                    )
                )
            ]
        );
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            $mockHttpClient
        );
        
        $mockAPIClient->removeCertification(
            ModelFactory::getTestableUser(),
            ModelFactory::getTestableCertificationGraduation(),
            'fake user jwt'
        );
    }
}
