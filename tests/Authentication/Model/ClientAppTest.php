<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Model;

use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * ClientAppTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\Model
 */
class ClientAppTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCommon() : void
    {
        $model = ModelFactory::getTestableClientApp();
        
        $actual = Normalizer::getInstance()
                            ->normalize($model);
        
        $expected = [];
        
        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }
    
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupMain() : void
    {
        $serializer = Normalizer::getInstance();
        
        $model = ModelFactory::getTestableClientApp();
        
        $actual = $serializer->normalize(
            $model,
            [AbstractNormalizer::GROUPS => ['main']]
        );
        
        $expected = [
            'uid'                 => $model->getUid(),
            'name'                => $model->getName(),
            'googleOAuthClientId' => $model->getGoogleOAuthClientId(),
        ];
        
        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }
    
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCreate() : void
    {
        $serializer = Normalizer::getInstance();
        
        $model = ModelFactory::getTestableClientApp();
        
        $actual = $serializer->normalize(
            $model,
            [AbstractNormalizer::GROUPS => ['create']]
        );
        
        $expected = [
            'googleOAuthClientId' => $model->getGoogleOAuthClientId(),
            'name'                => $model->getName(),
        ];
        
        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }
    
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupUpdate() : void
    {
        $serializer = Normalizer::getInstance();
        
        $model = ModelFactory::getTestableClientApp();
        
        $actual = $serializer->normalize(
            $model,
            [AbstractNormalizer::GROUPS => ['update']]
        );
        
        $expected = [
            'googleOAuthClientId' => $model->getGoogleOAuthClientId(),
            'name'                => $model->getName(),
        ];
        
        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }
}
