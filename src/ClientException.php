<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client;

use RuntimeException;

/**
 * ClientException
 *
 * @package Jalismrs\Stalactite\Service
 */
class ClientException extends
    RuntimeException
{
    ////////////////////
    /** Common Error **/
    ////////////////////

    /**
     * The client received an invalid response from the Stalactite API
     */
    public const INVALID_API_RESPONSE = 0;

    /**
     * An error occurred while contacting an API
     */
    public const CLIENT_TRANSPORT = 1;

    ////////////////////////////
    /** Authentication Error **/
    ////////////////////////////

    /**
     * Expired user JWT
     */
    public const EXPIRED_JWT = 13;

    /**
     * Invalid JWT issuer
     */
    public const INVALID_JWT_ISSUER = 12;

    /**
     * Invalid user JWT signature
     */
    public const INVALID_JWT_SIGNATURE = 15;

    /**
     * Invalid user JWT string used
     */
    public const INVALID_JWT_STRING = 11;

    /**
     * Invalid JWT structure (missing fields)
     */
    public const INVALID_JWT_STRUCTURE = 16;

    /**
     * Wrong user type set for the JWT
     */
    public const INVALID_JWT_USER_TYPE = 14;

    /**
     * Invalid Stalactite RSA public key used
     */
    public const INVALID_STALACTITE_RSA_PUBLIC_KEY = 10;
}
