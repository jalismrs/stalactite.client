<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Access\Model;

use Jalismrs\Stalactite\Client\Tests\Access\ModelFactory;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\TestCase;

/**
 * DomainUserRelationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Access\Model
 */
class DomainUserRelationTest extends
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
        
        $model = ModelFactory::getTestableDomainUserRelation();
        
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
    
        $model = ModelFactory::getTestableDomainUserRelation();
        
        $actual = $serializer->normalize(
            $model,
            [
                'groups' => [
                    'main',
                ],
            ]
        );
        
        $expected = [
            'uid'    => $model->getUid(),
            'domain' => $serializer->normalize(
                $model->getDomain(),
                [
                    'groups' => [
                        'main',
                    ],
                ]
            ),
            'user'   => $serializer->normalize(
                $model->getUser(),
                [
                    'groups' => [
                        'main',
                    ],
                ]
            ),
        ];
        
        self::assertEqualsCanonicalizing($expected, $actual);
    }
}
