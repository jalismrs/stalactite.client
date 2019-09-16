<?php

namespace jalismrs\Stalactite\Client;

use Exception;

class ClientException extends Exception
{
    ////////////////////
    /** Common Error **/
    ////////////////////

    /**
     * The client received an invalid response from the Stalactite API
     */
    public const INVALID_API_RESPONSE_ERROR = 0;

    /**
     * An error occurred while contacting an API
     */
    public const CLIENT_TRANSPORT_ERROR = 1;

    /**
     * Invalid parameter passed to the API client
     */
    public const INVALID_PARAMETER_PASSED_TO_CLIENT = 2;

    ////////////////////////////
    /** Authentication Error **/
    ////////////////////////////

    /**
     * Invalid Stalactite RSA public key used
     */
    public const INVALID_STALACTITE_RSA_PUBLIC_KEY_ERROR = 10;

    /**
     * Invalid user JWT string used
     */
    public const INVALID_USER_JWT_ERROR = 11;

    /**
     * Valid user JWT but wrong format (missing or wrong fields)
     */
    public const INVALID_USER_JWT_FORMAT_ERROR = 12;

    /**
     * Expired user JWT
     */
    public const EXPIRED_USER_JWT_ERROR = 13;

    /**
     * Wrong user type set for the JWT
     */
    public const INVALID_JWT_USER_TYPE_ERROR = 14;

    /**
     * Invalid user JWT signature
     */
    public const INVALID_USER_JWT_SIGNATURE_ERROR = 15;
}