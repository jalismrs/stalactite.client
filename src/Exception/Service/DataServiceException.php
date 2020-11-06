<?php

namespace Jalismrs\Stalactite\Client\Exception\Service;

/**
 * Class DataServiceException
 *
 * @package Jalismrs\Stalactite\Client\Exception\Service
 *
 * @codeCoverageIgnore
 */
class DataServiceException extends
    ServiceException
{
    public const INVALID_MODEL = 1;

    public const MISSING_USER_UID = 10;
    public const MISSING_POST_UID = 11;
    public const MISSING_PERMISSION_UID = 12;
    public const MISSING_DOMAIN_UID = 13;
    public const MISSING_CUSTOMER_UID = 14;
    public const MISSING_DOMAIN_USER_RELATION_UID = 15;
    public const MISSING_DOMAIN_CUSTOMER_RELATION_UID = 16;
}
