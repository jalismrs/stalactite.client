<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Domain;

use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Tests\AbstractTestEndpoint;
use Jalismrs\Stalactite\Client\Tests\ClientFactory;
use Jalismrs\Stalactite\Client\Tests\Data\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Tests\JwtFactory;
use Jalismrs\Stalactite\Client\Tests\MockHttpClientFactory;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class EndpointGetByNameTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Domain
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Domain\Service
 */
class EndpointGetByNameTest extends
    AbstractTestEndpoint
{
    use SystemUnderTestTrait;

    /**
     * @throws ClientException
     * @throws NormalizerException
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public function testGetByName(): void
    {
        $testClient = ClientFactory::createClient();
        $testClient->setHttpClient(
            MockHttpClientFactory::create(
                json_encode(
                    Normalizer::getInstance()
                        ->normalize(
                            TestableModelFactory::getTestableDomain(),
                            [
                                AbstractNormalizer::GROUPS => ['main'],
                            ]
                        ),
                    JSON_THROW_ON_ERROR
                )
            )
        );

        $systemUnderTest = $this->createSystemUnderTest($testClient);

        $response = $systemUnderTest->getByName(
            TestableModelFactory::getTestableDomain()
                ->getName(),
            JwtFactory::create()
        );

        self::assertInstanceOf(
            Domain::class,
            $response->getBody()
        );
    }

    /**
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function testRequestMethodCalledOnce(): void
    {
        $mockClient = $this->createMockClient();
        $systemUnderTest = $this->createSystemUnderTest($mockClient);

        $systemUnderTest->getByName(
            TestableModelFactory::getTestableDomain()
                ->getName(),
            JwtFactory::create()
        );
    }
}
