<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Data\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * Class AccessClearanceTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Model\AccessClearance
 */
class AccessClearanceTest extends
    TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function testGroupCommon() : void
    {
        $model = TestableModelFactory::getTestableAccessClearance();
        
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
        $model = TestableModelFactory::getTestableAccessClearance();
        
        $actual = Normalizer::getInstance()
                            ->normalize(
                                $model,
                                [AbstractNormalizer::GROUPS => ['main']]
                            );
        
        $expected = [
            'granted' => $model->isGranted(),
            'type'    => $model->getType(),
        ];
        
        self::assertEqualsCanonicalizing(
            $expected,
            $actual
        );
    }
    
    /**
     * @param AccessClearance $accessClearance
     * @param string          $expected
     *
     * @dataProvider getAccessClearances
     */
    public function testHasUserAccessGranted(
        AccessClearance $accessClearance,
        string $expected = ''
    ) : void {
        if ($expected === 'user') {
            self::assertTrue($accessClearance->hasUserAccessGranted());
        } else {
            self::assertFalse($accessClearance->hasUserAccessGranted());
        }
    }
    
    /**
     * @param AccessClearance $accessClearance
     * @param string          $expected
     *
     * @dataProvider getAccessClearances
     */
    public function testHasAdminAccessGranted(
        AccessClearance $accessClearance,
        string $expected = ''
    ) : void {
        if ($expected === 'admin') {
            self::assertTrue($accessClearance->hasAdminAccessGranted());
        } else {
            self::assertFalse($accessClearance->hasAdminAccessGranted());
        }
    }
    
    public function getAccessClearances() : array
    {
        return [
            [
                new AccessClearance(
                    false,
                    AccessClearance::NO_ACCESS
                ),
            ],
            [
                new AccessClearance(
                    false,
                    AccessClearance::USER_ACCESS
                ),
            ],
            [
                new AccessClearance(
                    false,
                    AccessClearance::ADMIN_ACCESS
                ),
            ],
            [
                new AccessClearance(
                    true,
                    AccessClearance::NO_ACCESS
                ),
            ],
            [
                new AccessClearance(
                    true,
                    AccessClearance::USER_ACCESS
                ),
                'user',
            ],
            [
                new AccessClearance(
                    true,
                    AccessClearance::ADMIN_ACCESS
                ),
                'admin',
            ],
        ];
    }
}
