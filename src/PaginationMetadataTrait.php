<?php

namespace Jalismrs\Stalactite\Client;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use InvalidArgumentException;

trait PaginationMetadataTrait
{
    public static function getPaginationSchemaFor(string $class): array
    {
        if (!is_subclass_of($class, Schemable::class)) {
            throw new InvalidArgumentException(sprintf('%s must be an instance of %s', $class, Schemable::class));
        }

        /** @var Schemable $class */
        return [
            '_metas' => ['type' => JsonSchema::OBJECT_TYPE, 'schema' => [
                'total' => ['type' => JsonRule::INTEGER_TYPE],
                'pageSize' => ['type' => JsonRule::INTEGER_TYPE]
            ]],
            'results' => ['type' => JsonSchema::LIST_TYPE, 'schema' => $class::getSchema()]
        ];
    }
}