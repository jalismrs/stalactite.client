<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Api\Data\AuthToken\Domain;

use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Data\AuthToken\Domain\Service;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ApiGetByNameTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Api\Data\AuthToken\Domain
 */
class ApiGetByNameTest extends
    TestCase
{
    /**
     * testGetByName
     *
     * @return void
     *
     * @throws ClientException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testGetByName() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => null,
                        'domains' => [
                            Serializer::getInstance()
                                      ->normalize(
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
        );
        
        $response = $mockService->getByName(
            ModelFactory::getTestableDomain()
                        ->getName(),
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
     * testThrowOnInvalidResponseGetByName
     *
     * @return void
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function testThrowOnInvalidResponseGetByName() : void
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionCode(ClientException::INVALID_API_RESPONSE);
        
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        $mockClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    [
                        'success' => true,
                        'error'   => null,
                        'domains' => Serializer::getInstance()
                                               ->normalize(
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
        );
        
        $mockService->getByName(
            ModelFactory::getTestableDomain()
                        ->getName(),
            'fake API auth token'
        );
    }
}
