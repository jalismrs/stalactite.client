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
 * PostTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 *
 * @covers  \Jalismrs\Stalactite\Client\Data\Model\Post
 */
class PostTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCommon() : void
    {
        $model = TestableModelFactory::getTestablePost();
        $model->addPermission(TestableModelFactory::getTestablePermission());
        
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
        $normalizer        = Normalizer::getInstance();
        $normalizerContext = [
            AbstractNormalizer::GROUPS => ['main'],
        ];
        
        $model = TestableModelFactory::getTestablePost();
        $model->addPermission(TestableModelFactory::getTestablePermission());
        
        $actual = $normalizer->normalize(
            $model,
            $normalizerContext
        );
        
        $permissions = [];
        foreach ($model->getPermissions() as $permission) {
            $permissions[] = $normalizer->normalize(
                $permission,
                $normalizerContext
            );
        }
        
        $expected = [
            'uid'         => $model->getUid(),
            'name'        => $model->getName(),
            'shortName'   => $model->getShortName(),
            'permissions' => $permissions,
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
        $normalizer        = Normalizer::getInstance();
        $normalizerContext = [
            AbstractNormalizer::GROUPS => ['create'],
        ];
        
        $model = TestableModelFactory::getTestablePost();
        $model->addPermission(TestableModelFactory::getTestablePermission());
        
        $actual = $normalizer->normalize(
            $model,
            $normalizerContext
        );
        
        $permissions = [];
        foreach ($model->getPermissions() as $permission) {
            $permissions[] = $normalizer->normalize(
                $permission,
                $normalizerContext
            );
        }
        
        $expected = [
            'name'        => $model->getName(),
            'shortName'   => $model->getShortName(),
            'permissions' => $permissions,
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
        
        $model = TestableModelFactory::getTestablePost();
        $model->addPermission(TestableModelFactory::getTestablePermission());
        
        $actual = $serializer->normalize(
            $model,
            [
                AbstractNormalizer::GROUPS => ['update'],
            ]
        );
        
        $expected = [
            'name'      => $model->getName(),
            'shortName' => $model->getShortName(),
        ];
        
        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }
    
    public function testPermissions() : void
    {
        $post = TestableModelFactory::getTestablePost();
        
        $permission  = TestableModelFactory::getTestablePermission()
                                           ->setScope('p1');
        $permission2 = TestableModelFactory::getTestablePermission()
                                           ->setScope('p2');
        
        self::assertFalse($post->hasPermission((string)$permission));
        self::assertFalse($post->hasPermission((string)$permission2));
        
        $post->addPermission($permission);
        
        self::assertTrue($post->hasPermission((string)$permission));
        self::assertFalse($post->hasPermission((string)$permission2));
        
        $post->addPermission($permission2);
        
        self::assertTrue($post->hasPermission((string)$permission));
        self::assertTrue($post->hasPermission((string)$permission2));
    }
}
