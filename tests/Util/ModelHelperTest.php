<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Util;

use InvalidArgumentException;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * ModelHelperTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Util
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
        $domain  = ModelFactory::getTestableDomain()
                               ->setUid('azerty');
        $domain2 = ModelFactory::getTestableDomain()
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
                ModelFactory::getTestableDomain(),
            ],
            User::class
        );
    }
}
