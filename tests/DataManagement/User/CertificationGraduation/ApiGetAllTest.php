<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\DataManagement\User\CertificationGraduation;

use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\CertificationGraduationModel;
use Jalismrs\Stalactite\Client\DataManagement\User\CertificationGraduation\Client;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiGetAllTest
 *
 * @package Jalismrs\Stalactite\Test\DataManagement\User\CertificationGraduation
 */
class ApiGetAllTest extends
    TestCase
{
    /**
     * testGetAll
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
    public function testGetAll() : void
    {
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'        => true,
                                'error'          => null,
                                'certifications' => [
                                    ModelFactory::getTestableCertificationGraduation()
                                                ->asArray()
                                ]
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $response = $mockAPIClient->getAll(
            ModelFactory::getTestableUser(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertContainsOnlyInstancesOf(
            CertificationGraduationModel::class,
            $response->getData()['certifications']
        );
    }
    
    /**
     * testThrowExceptionOnInvalidResponseGetAll
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetAll() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockAPIClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success'        => true,
                                'error'          => null,
                                'certifications' => ModelFactory::getTestableCertificationGraduation()
                                                                ->asArray()
                                // invalid type
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $mockAPIClient->getAll(
            ModelFactory::getTestableUser(),
            'fake user jwt'
        );
    }
}
