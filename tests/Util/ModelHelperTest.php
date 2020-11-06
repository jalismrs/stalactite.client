<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Util;

use InvalidArgumentException;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Tests\Data\Model\TestableModelFactory;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * ModelHelperTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Util
 *
 * @covers \Jalismrs\Stalactite\Client\Util\ModelHelper
 */
class ModelHelperTest extends
    TestCase
{
    /**
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetUids() : void
    {
        $domain  = TestableModelFactory::getTestableDomain()
                                       ->setUid('azerty');
        $domain2 = TestableModelFactory::getTestableDomain()
                                       ->setUid('uiop');
        
        static::assertEquals(
            [
                $domain->getUid(),
                $domain2->getUid()
            ],
            ModelHelper::getUids(
                [
                    $domain,
                    $domain2
                ]
            )
        );
    }
    
    /**
     * @throws InvalidArgumentException
     */
    public function testThrowOnGetUidsWithNonModelList() : void
    {
        $this->expectException(InvalidArgumentException::class);
        
        ModelHelper::getUids(
            [
                'not a model',
            ]
        );
    }
    
    /**
     * @throws InvalidArgumentException
     */
    public function testThrowOnGetUidsWithInvalidModelTypeList() : void
    {
        $this->expectException(InvalidArgumentException::class);
        
        ModelHelper::getUids(
            [
                TestableModelFactory::getTestableDomain(),
            ],
            User::class
        );
    }
}
