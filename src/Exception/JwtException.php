<?php

namespace Jalismrs\Stalactite\Client\Exception;

use Exception;

class JwtException extends Exception
{
    /**
     * Invalid JWT issuer
     */
    public const INVALID_JWT_ISSUER = 1;

    /**
     * Wrong user type set for the JWT
     */
    public const INVALID_JWT_USER_TYPE = 2;

    /**
     * Invalid user JWT signature
     */
    public const INVALID_JWT_SIGNATURE = 3;

    /**
     * JWT signature is missing
     */
    public const MISSING_JWT_SIGNATURE = 4;

    /**
     * Invalid JWT structure (missing fields)
     */
    public const INVALID_JWT_STRUCTURE = 5;

    /**
     * Expired user JWT
     */
    public const EXPIRED_JWT = 6;

    /**
     * Invalid Stalactite RSA public key used
     */
    public const INVALID_STALACTITE_RSA_PUBLIC_KEY = 10;
}