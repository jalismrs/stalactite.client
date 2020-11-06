<?php

namespace Jalismrs\Stalactite\Client;

/**
 * Interface Schemable
 *
 * @package Jalismrs\Stalactite\Client
 */
interface Schemable
{
    /**
     * getSchema
     *
     * @static
     * @return array
     */
    public static function getSchema(): array;
}
