<?php

namespace Jalismrs\Stalactite\Client\Exception;

use Exception;

class JwtException extends Exception
{
    /**
     * Invalid user JWT string used
     */
    public const INVALID_JWT_STRING = 1;

    /**
     * Invalid JWT issuer
     */
    public const INVALID_JWT_ISSUER = 2;

    /**
     * Wrong user type set for the JWT
     */
    public const INVALID_JWT_USER_TYPE = 3;

    /**
     * Invalid user JWT signature
     */
    public const INVALID_JWT_SIGNATURE = 4;

    /**
     * JWT signature is missing
     */
    public const MISSING_JWT_SIGNATURE = 5;

    /**
     * Invalid JWT structure (missing fields)
     */
    public const INVALID_JWT_STRUCTURE = 6;

    /**
     * Expired user JWT
     */
    public const EXPIRED_JWT = 7;

    /**
     * Invalid Stalactite RSA public key used
     */
    public const INVALID_STALACTITE_RSA_PUBLIC_KEY = 10;
}