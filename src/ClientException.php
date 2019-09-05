<?php

namespace jalismrs\Stalactite\Client;

use Exception;

class ClientException extends Exception
{
    /**
     * The client received an invalid response from the Stalactite API
     */
    public const INVALID_API_RESPONSE_ERROR = 0;

    /**
     * An error occurred while contacting the API
     */
    public const CLIENT_TRANSPORT_ERROR = 1;

    /**
     * Invalid Stalactite RSA public key used
     */
    public const INVALID_STALACTITE_RSA_PUBLIC_KEY_ERROR = 2;

    /**
     * Invalid user JWT string used
     */
    public const INVALID_USER_JWT_ERROR = 3;

    /**
     * Invalid parameter passed to the API client
     */
    public const INVALID_PARAMETER_PASSED_TO_CLIENT = 4;
}