<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Exception;

use RuntimeException;

/**
 * ValidatorException
 *
 * @package Jalismrs\Stalactite\Client\Exception
 */
class ValidatorException extends
    RuntimeException implements
    ExceptionInterface
{
    public const INVALID_SCHEMA = 1;
    public const INVALID_DATA = 2;
    public const INVALID_DATA_TYPE = 3;
    public const INVALID_DATA_SET = 4;
}
