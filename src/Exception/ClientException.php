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
}
