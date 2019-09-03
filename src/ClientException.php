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
}