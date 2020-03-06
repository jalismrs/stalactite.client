<?php

namespace Jalismrs\Stalactite\Client\Exception\Service;

class DataServiceException extends ServiceException
{
    public const INVALID_MODEL = 1;

    public const MISSING_USER_UID = 10;
    public const MISSING_POST_UID = 11;
    public const MISSING_DOMAIN_UID = 12;
}