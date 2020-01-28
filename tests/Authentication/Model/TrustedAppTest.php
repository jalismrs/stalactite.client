<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Model;

use Jalismrs\Stalactite\Client\Tests\Authentication\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * TrustedAppTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Access
 */
class TrustedAppTest extends
    TestCase
{
    /**
     * testGroupCommon
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public function testGroupCommon() : void
    {
        $serializer = Serializer::getInstance();
    
        $model = ModelFactory::getTestableTrustedApp();
        
        $actual = $serializer->normalize($model);
        
        $expected = [];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
    
    /**
     * testGroupMain
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public function testGroupMain() : void
    {
        $serializer = Serializer::getInstance();
    
        $model = ModelFactory::getTestableTrustedApp();
        
        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'main',
                ],
            ]
        );
        
        $expected = [
            'uid'                 => $model->getUid(),
            'name'                => $model->getName(),
            'authToken'           => $model->getAuthToken(),
            'googleOAuthClientId' => $model->getGoogleOAuthClientId(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
    
    /**
     * testGroupReset
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public function testGroupReset() : void
    {
        $serializer = Serializer::getInstance();
    
        $model = ModelFactory::getTestableTrustedApp();
        
        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'reset',
                ],
            ]
        );
        
        $expected = [
            'resetToken' => $model->getResetToken(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
    
    /**
     * testGroupCreate
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public function testGroupCreate() : void
    {
        $serializer = Serializer::getInstance();
    
        $model = ModelFactory::getTestableTrustedApp();
        
        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'create',
                ],
            ]
        );
        
        $expected = [
            'googleOAuthClientId' => $model->getGoogleOAuthClientId(),
            'name'                => $model->getName(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
    
    /**
     * testGroupUpdate
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public function testGroupUpdate() : void
    {
        $serializer = Serializer::getInstance();
    
        $model = ModelFactory::getTestableTrustedApp();
        
        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => [
                    'update',
                ],
            ]
        );
        
        $expected = [
            'googleOAuthClientId' => $model->getGoogleOAuthClientId(),
            'name'                => $model->getName(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
