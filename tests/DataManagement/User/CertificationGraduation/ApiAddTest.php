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
 * ApiAddTest
 *
 * @package Jalismrs\Stalactite\Test\DataManagement\User\CertificationGraduation
 */
class ApiAddTest extends
    TestCase
{
    /**
     * testAdd
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testAdd() : void
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
        
        $response = $mockAPIClient->add(
            ModelFactory::getTestableUser(),
            ModelFactory::getTestableCertificationGraduation(),
            'fake user jwt'
        );
        self::assertTrue($response->success());
        self::assertNull($response->getError());
    }
    
    /**
     * testThrowExceptionOnInvalidResponseAdd
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseAdd() : void
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
        
        $mockAPIClient->add(
            ModelFactory::getTestableUser(),
            ModelFactory::getTestableCertificationGraduation(),
            'fake user jwt'
        );
    }
    
    /**
     * testThrowExceptionOnInvalidCertificationTypeAdd
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidCertificationTypeAdd() : void
    {
        // TODO: voir pourquoi il n'y a pas de mockHttpClient
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT);
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            null
        //$mockHttpClient
        );
        
        $mockAPIClient->add(
            ModelFactory::getTestableUser(),
            ModelFactory::getTestableCertificationGraduation()
                        ->setType(null),
            'fake user jwt'
        );
    }
}
