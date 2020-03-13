<?php

namespace Jalismrs\Stalactite\Client\Exception\Service;

class AccessServiceException extends ServiceException
{
    public const MISSING_USER_UID = 1;
    public const MISSING_DOMAIN_UID = 2;
}