<?php

namespace Jalismrs\Stalactite\Client\Tests;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\PaginationMetadataTrait;
use Jalismrs\Stalactite\Client\Schemable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class PaginationMetadataTraitTest
 * @package Jalismrs\Stalactite\Client\Tests
 * @covers \Jalismrs\Stalactite\Client\PaginationMetadataTrait
 */
class PaginationMetadataTraitTest extends TestCase
{
    /** @var PaginationMetadataTrait|MockObject|null $mockTrait */
    private ?MockObject $mockTrait = null;

    public function setUp(): void
    {
        $this->mockTrait = $this->getMockBuilder(PaginationMetadataTrait::class)->getMockForTrait();
    }

    public function testGetPaginationSchemaFor(): void
    {
        $schemable = new class implements Schemable {
            public static function getSchema(): array
            {
                return [
                    'test' => ['type' => JsonRule::STRING_TYPE]
                ];
            }
        };

        self::assertEqualsCanonicalizing([
            '_metas' => ['type' => JsonSchema::OBJECT_TYPE, 'schema' => [
                'total' => ['type' => JsonRule::INTEGER_TYPE],
                'pageSize' => ['type' => JsonRule::INTEGER_TYPE]
            ]],
            'results' => ['type' => JsonSchema::LIST_TYPE, 'schema' => $schemable::getSchema()]
        ], $this->mockTrait::getPaginationSchemaFor(get_class($schemable)));
    }

    public function testGetPaginationThrowOnInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->mockTrait::getPaginationSchemaFor('invalid-class');
    }
}