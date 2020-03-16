<?php

namespace Jalismrs\Stalactite\Client\Exception\Service;

class AccessServiceException extends ServiceException
{
    public const MISSING_USER_UID = 1;
    public const MISSING_CUSTOMER_UID = 2;
    public const MISSING_DOMAIN_UID = 3;
    public const MISSING_DOMAIN_RELATION_UID = 4;
}