<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\DataManagement\User;

use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\UserModel;
use Jalismrs\Stalactite\Client\DataManagement\User\Client;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * ApiGetByEmailAndGoogleIdTest
 *
 * @package Jalismrs\Stalactite\Test\DataManagement\User
 */
class ApiGetByEmailAndGoogleIdTest extends
    TestCase
{
    /**
     * testGetByEmailAndGoogleId
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testGetByEmailAndGoogleId() : void
    {
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error'   => null,
                                'user'    => ModelFactory::getTestableUser()
                                                         ->asArray()
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $userModel = ModelFactory::getTestableUser();
        
        $response = $mockAPIClient->getByEmailAndGoogleId(
            $userModel->getEmail(),
            $userModel->getGoogleId(),
            'fake user jwt'
        );
        self::assertTrue($response->isSuccess());
        self::assertNull($response->getError());
        self::assertInstanceOf(
            UserModel::class,
            $response->getData()['user']
        );
    }
    
    /**
     * testThrowExceptionOnInvalidResponseGetByEmailAndGoogleId
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function testThrowExceptionOnInvalidResponseGetByEmailAndGoogleId() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE_ERROR);
        
        $mockAPIClient = new Client(
            'http://fakeClient',
            null,
            new MockHttpClient(
                [
                    new MockResponse(
                        json_encode(
                            [
                                'success' => true,
                                'error'   => null,
                                'user'    => []
                            ],
                            JSON_THROW_ON_ERROR
                        )
                    )
                ]
            )
        );
        
        $userModel = ModelFactory::getTestableUser();
        
        $mockAPIClient->getByEmailAndGoogleId(
            $userModel->getEmail(),
            $userModel->getGoogleId(),
            'fake user jwt'
        );
    }
}
