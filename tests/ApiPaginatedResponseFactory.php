<?php

namespace Jalismrs\Stalactite\Client\Tests;

use Exception;

class ApiPaginatedResponseFactory
{
    /**
     * @return array
     * @throws Exception
     */
    private static function getRandomMetadata(): array
    {
        return [
            'total' => random_int(100, 1000),
            'pageSize' => random_int(10, 50)
        ];
    }

    /**
     * @param $results
     * @return array
     * @throws Exception
     */
    public static function getFor($results): array
    {
        return [
            '_metas' => self::getRandomMetadata(),
            'results' => $results
        ];
    }
}