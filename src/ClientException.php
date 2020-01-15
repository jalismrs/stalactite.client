<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

use RuntimeException;

/**
 * ClientException
 *
 * @package Jalismrs\Stalactite\Client
 */
class ClientException extends
    RuntimeException
{
    ////////////////////
    /** Common Error **/
    ////////////////////
    
    /**
     * An error occurred while contacting an API
     */
    public const CLIENT_TRANSPORT_ERROR = 1;
    /**
     * The client received an invalid response from the Stalactite API
     */
    public const INVALID_API_RESPONSE_ERROR = 0;
    /**
     * Invalid parameter passed to the API client
     */
    public const INVALID_PARAMETER_PASSED_TO_CLIENT = 2;
    
    ////////////////////////////
    /** Authentication Error **/
    ////////////////////////////
    
    /**
     * Expired user JWT
     */
    public const EXPIRED_JWT_ERROR = 13;
    /**
     * Invalid JWT issuer
     */
    public const INVALID_JWT_ISSUER_ERROR = 12;
    /**
     * Invalid user JWT signature
     */
    public const INVALID_JWT_SIGNATURE_ERROR = 15;
    /**
     * Invalid user JWT string used
     */
    public const INVALID_JWT_STRING_ERROR = 11;
    /**
     * Invalid JWT structure (missing fields)
     */
    public const INVALID_JWT_STRUCTURE_ERROR = 16;
    /**
     * Wrong user type set for the JWT
     */
    public const INVALID_JWT_USER_TYPE_ERROR = 14;
    /**
     * Invalid Stalactite RSA public key used
     */
    public const INVALID_STALACTITE_RSA_PUBLIC_KEY_ERROR = 10;
}
