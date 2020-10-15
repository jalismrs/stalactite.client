<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * CustomerTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Model\Customer
 */
class CustomerTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCommon() : void
    {
        $model = TestableModelFactory::getTestableCustomer();
        
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
        $model = TestableModelFactory::getTestableCustomer();
        
        $actual = Normalizer::getInstance()
                            ->normalize(
                                $model,
                                [
                                    AbstractNormalizer::GROUPS => [
                                        'main',
                                    ],
                                ]
                            );
        
        $expected = [
            'uid'       => $model->getUid(),
            'googleId'  => $model->getGoogleId(),
            'email'     => $model->getEmail(),
            'firstName' => $model->getFirstName(),
            'lastName'  => $model->getLastName(),
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
        $model = TestableModelFactory::getTestableCustomer();
        
        $actual = Normalizer::getInstance()
                            ->normalize(
                                $model,
                                [
                                    AbstractNormalizer::GROUPS => [
                                        'create',
                                    ],
                                ]
                            );
        
        $expected = [
            'email'     => $model->getEmail(),
            'firstName' => $model->getFirstName(),
            'lastName'  => $model->getLastName(),
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
        $model = TestableModelFactory::getTestableCustomer();
        
        $actual = Normalizer::getInstance()
                            ->normalize(
                                $model,
                                [
                                    AbstractNormalizer::GROUPS => [
                                        'update',
                                    ],
                                ]
                            );
        
        $expected = [
            'email'     => $model->getEmail(),
            'firstName' => $model->getFirstName(),
            'lastName'  => $model->getLastName(),
        ];
        
        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }
}
