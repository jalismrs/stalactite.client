<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Exception;

use Exception;
use Jalismrs\Stalactite\Client\Util\Response;
use Throwable;

/**
 * Class ClientException
 *
 * @package Jalismrs\Stalactite\Client\Exception
 *
 * @codeCoverageIgnore
 */
class ClientException extends
    Exception
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

    private ?Response $response;

    /**
     * ClientException constructor.
     *
     * @param Response|null $response
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        ?Response $response = null,
        $message = "",
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct(
            $message,
            $code,
            $previous
        );
        $this->response = $response;
    }

    /**
     * getResponse
     *
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
