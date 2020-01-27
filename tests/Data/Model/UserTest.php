<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Tests\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\TestCase;

/**
 * UserTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 */
class UserTest extends
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
        $serializer = Serializer::create();
        
        $model = ModelFactory::getTestableUser();
        
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
        $serializer = Serializer::create();
        
        $model = ModelFactory::getTestableUser();
        
        $actual = $serializer->normalize(
            $model,
            [
                'groups' => [
                    'main',
                ],
            ]
        );
        
        $expected = [
            'uid'       => $model->getUid(),
            'firstName' => $model->getFirstName(),
            'lastName'  => $model->getLastName(),
            'email'     => $model->getEmail(),
            'googleId'  => $model->getGoogleId(),
            'admin'     => $model->isAdmin(),
            'leads'     => $serializer->normalize(
                $model->getLeads(),
                [
                    'groups' => [
                        'main',
                    ],
                ]
            ),
            'posts'     => $serializer->normalize(
                $model->getPosts(),
                [
                    'groups' => [
                        'main',
                    ],
                ]
            ),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
    
    /**
     * testGroupMin
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
    public function testGroupMin() : void
    {
        $serializer = Serializer::create();
        
        $model = ModelFactory::getTestableUser();
        
        $actual = $serializer->normalize(
            $model,
            [
                'groups' => [
                    'min',
                ],
            ]
        );
        
        $expected = [
            'uid'       => $model->getUid(),
            'firstName' => $model->getFirstName(),
            'lastName'  => $model->getLastName(),
            'email'     => $model->getEmail(),
            'googleId'  => $model->getGoogleId(),
            'admin'     => $model->isAdmin(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
    
    /**
     * testGroupUpdateMe
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
    public function testGroupUpdateMe() : void
    {
        $serializer = Serializer::create();
        
        $model = ModelFactory::getTestableUser();
        
        $actual = $serializer->normalize(
            $model,
            [
                'groups' => [
                    'updateMe',
                ],
            ]
        );
        
        $expected = [
            'firstName' => $model->getFirstName(),
            'lastName'  => $model->getLastName(),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
