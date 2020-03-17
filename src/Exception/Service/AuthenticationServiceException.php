<?php

namespace Jalismrs\Stalactite\Client\Exception\Service;

class AuthenticationServiceException extends ServiceException
{
    public const MISSING_TRUSTED_APP_UID = 1;
}