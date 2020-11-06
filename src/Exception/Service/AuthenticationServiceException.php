<?php

namespace Jalismrs\Stalactite\Client\Exception\Service;

/**
 * Class AuthenticationServiceException
 *
 * @package Jalismrs\Stalactite\Client\Exception\Service
 *
 * @codeCoverageIgnore
 */
class AuthenticationServiceException extends
    ServiceException
{
    public const INVALID_TOKEN = 1;

    public const MISSING_CLIENT_APP_UID = 10;
    public const MISSING_SERVER_APP_UID = 11;
}
