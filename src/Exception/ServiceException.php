<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Exception;

use Exception;

/**
 * ServiceException
 *
 * @package Jalismrs\Stalactite\Client\Exception
 */
class ServiceException extends Exception
{
    public const MISSING_TRUSTED_APP_UID = 1;
}
