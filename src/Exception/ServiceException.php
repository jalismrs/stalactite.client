<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Exception;

use RuntimeException;

/**
 * ServiceException
 *
 * @package Jalismrs\Stalactite\Client\Exception
 */
class ServiceException extends
    RuntimeException implements
    ExceptionInterface
{
}
