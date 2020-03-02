<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Exception;

use Exception;

/**
 * ClientException
 *
 * @package Jalismrs\Stalactite\Client\Exception
 */
class ClientException extends Exception
{
    ///////////////////////
    /** Transport Error **/
    ///////////////////////

    /**
     * An error occurred while performing an HTTP request
     */
    public const REQUEST_FAILED = 1;

    //////////////////////
    /** Response Error **/
    //////////////////////

    /**
     * Thrown on invalid Stalactite API response
     */
    public const INVALID_RESPONSE = 10;

    /**
     * Thrown on invalid Stalactite API json response
     */
    public const INVALID_JSON_RESPONSE = 11;

    /**
     * Thrown when the Stalactite API response does not match the expected endpoint schema
     */
    public const INVALID_RESPONSE_FORMAT = 12;

    ////////////////////////////
    /** Authentication Error **/
    ////////////////////////////

    /**
     * Expired user JWT
     */
    public const EXPIRED_JWT = 20;

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
