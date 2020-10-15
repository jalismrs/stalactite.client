<?php

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Model;

use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class ServerAppTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\Model
 *
 * @covers \Jalismrs\Stalactite\Client\Authentication\Model\ServerApp
 */
class ServerAppTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCommon() : void
    {
        $model = ModelFactory::getTestableServerApp();
        
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
        
        $model = ModelFactory::getTestableServerApp();
        
        $actual = $serializer->normalize(
            $model,
            [AbstractNormalizer::GROUPS => ['main']]
        );
        
        $expected = [
            'uid'               => $model->getUid(),
            'name'              => $model->getName(),
            'tokenSignatureKey' => $model->getTokenSignatureKey(),
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
        
        $model = ModelFactory::getTestableServerApp();
        
        $actual = $serializer->normalize(
            $model,
            [AbstractNormalizer::GROUPS => ['create']]
        );
        
        $expected = ['name' => $model->getName()];
        
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
        
        $model = ModelFactory::getTestableServerApp();
        
        $actual = $serializer->normalize(
            $model,
            [AbstractNormalizer::GROUPS => ['update']]
        );
        
        $expected = ['name' => $model->getName()];
        
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
    public function testGroupResetTokenSignatureKey() : void
    {
        $serializer = Normalizer::getInstance();
        
        $model = ModelFactory::getTestableServerApp();
        
        $actual = $serializer->normalize(
            $model,
            [AbstractNormalizer::GROUPS => ['resetTokenSignatureKey']]
        );
        
        $expected = ['resetTokenSignatureKey' => $model->getTokenSignatureKey()];
        
        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }
}
