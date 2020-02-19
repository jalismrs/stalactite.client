<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

use Closure;
use ErrorException;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use OutOfBoundsException;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use Throwable;
use TypeError;
use function assert;
use function count;
use function gettype;
use function in_array;
use function is_bool;
use function is_scalar;
use function strtoupper;
use function trigger_error;
use function vsprintf;
use const E_USER_WARNING;

/**
 * Request
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
class Request
{
    /**
     * @var string
     */
    private $endpoint;
    /**
     * @var mixed|null
     */
    private $json;
    /**
     * @var string|null
     */
    private $jwt;
    /**
     * @var string
     */
    private $method;
    /**
     * @var array|null
     */
    private $normalization;
    /**
     * @var array
     */
    private $options = [];
    /**
     * @var array|null
     */
    private $queryParameters;
    /**
     * @var Closure|null
     */
    private $response;
    /**
     * @var array
     */
    private $uriParameters = [];
    /**
     * @var array|null
     */
    private $validation;

    /**
     * Request constructor.
     *
     * @param string $endpoint
     * @param string|null $method
     *
     * @throws RequestException
     */
    public function __construct(
        string $endpoint,
        string $method = 'GET'
    )
    {
        $this->endpoint = $endpoint;

        $throwable = null;
        try {
            $this->method = self::validateMethod($method);
        } catch (OutOfBoundsException $outOfBoundsException) {
            $throwable = $outOfBoundsException;
        } finally {
            if ($throwable instanceof Throwable) {
                throw new RequestException(
                    'error while setting method',
                    $throwable->getCode(),
                    $throwable
                );
            }
        }
    }

    /**
     * validateMethod
     *
     * @static
     *
     * @param string $method
     *
     * @return string
     *
     * @throws OutOfBoundsException
     */
    private static function validateMethod(string $method): string
    {
        $methods = [
            'GET',
            'DELETE',
            'PUT',
            'POST',
        ];

        if (!in_array(
            $method,
            $methods,
            true
        )) {
            $methodUpper = strtoupper($method);
            if (in_array(
                $methodUpper,
                $methods,
                true
            )) {
                trigger_error(
                    "Method '{$method}' should be upper case '{$methodUpper}'",
                    E_USER_WARNING
                );
            } else {
                throw new OutOfBoundsException(
                    "Invalid HTTP method '{$method}'"
                );
            }

            $method = $methodUpper;
        }

        return $method;
    }

    /**
     * getUri
     *
     * @return string
     */
    public function getUri(): string
    {
        return vsprintf(
            $this->getEndpoint(),
            $this->getUriParameters()
        );
    }

    /**
     * getEndpoint
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * getUriParameters
     *
     * @return array
     */
    public function getUriParameters(): array
    {
        return $this->uriParameters;
    }

    /**
     * setUriParameters
     *
     * @param array $uriParameters
     *
     * @return $this
     *
     * @throws RequestException
     */
    public function setUriParameters(array $uriParameters): self
    {
        $throwable = null;

        try {
            $this->uriParameters = self::validateUriParameters($uriParameters);
        } catch (TypeError $typeError) {
            $throwable = $typeError;
        } finally {
            if ($throwable instanceof Throwable) {
                throw new RequestException(
                    'error while setting uriParameters',
                    $throwable->getCode(),
                    $throwable
                );
            }
        }

        return $this;
    }

    /**
     * validateUriParameters
     *
     * @static
     *
     * @param array $uriParameters
     *
     * @return array
     *
     * @throws TypeError
     */
    private static function validateUriParameters(array $uriParameters): array
    {
        foreach ($uriParameters as $uriParameter) {
            if (!is_scalar($uriParameter)) {
                $type = gettype($uriParameter);
                throw new TypeError(
                    "Expected a scalar value, received '{$type}'"
                );
            }
            if (is_bool($uriParameter)) {
                trigger_error(
                    'boolean value in discouraged',
                    E_USER_WARNING
                );
            }
        }

        return $uriParameters;
    }

    /**
     * getMethod
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * getJson
     *
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * setJson
     *
     * @param $json
     *
     * @return $this
     */
    public function setJson($json): self
    {
        $this->json = $json;

        return $this;
    }

    /**
     * getQueryParameters
     *
     * @return array|null
     */
    public function getQueryParameters(): ?array
    {
        return $this->queryParameters;
    }

    /**
     * setQueryParameters
     *
     * @param array|null $queryParameters
     *
     * @return $this
     */
    public function setQueryParameters(?array $queryParameters): self
    {
        $this->queryParameters = $queryParameters;

        return $this;
    }

    /**
     * getJwt
     *
     * @return string|null
     */
    public function getJwt(): ?string
    {
        return $this->jwt;
    }

    /**
     * setJwt
     *
     * @param string|null $jwt
     *
     * @return $this
     */
    public function setJwt(?string $jwt): self
    {
        $this->jwt = $jwt;

        return $this;
    }

    /**
     * getNormalization
     *
     * @return array
     */
    public function getNormalization(): ?array
    {
        return $this->normalization;
    }

    /**
     * setNormalization
     *
     * @param array|null $normalization
     *
     * @return $this
     */
    public function setNormalization(?array $normalization): self
    {
        $this->normalization = $normalization;

        return $this;
    }

    /**
     * getResponse
     *
     * @return Closure|null
     */
    public function getResponse(): ?Closure
    {
        return $this->response;
    }

    /**
     * setResponse
     *
     * @param Closure|null $response
     *
     * @return $this
     *
     * @throws RequestException
     */
    public function setResponse(?Closure $response): self
    {
        $throwable = null;

        try {
            $this->response = self::validateResponse($response);
        } catch (ErrorException $errorException) {
            $throwable = $errorException;
        } catch (InvalidArgumentException $invalidArgumentException) {
            $throwable = $invalidArgumentException;
        } catch (TypeError $typeError) {
            $throwable = $typeError;
        } finally {
            if ($throwable instanceof Throwable) {
                throw new RequestException(
                    'error while setting response',
                    $throwable->getCode(),
                    $throwable
                );
            }
        }

        return $this;
    }

    /**
     * validateResponse
     *
     * @static
     *
     * @param Closure|null $response
     *
     * @return Closure|null
     *
     * @throws ErrorException
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    private static function validateResponse(?Closure $response): ?Closure
    {
        if ($response !== null) {
            try {
                $reflectionFunction = new ReflectionFunction($response);
            } catch (ReflectionException $reflectionException) {
                throw new ErrorException(
                    'should never happen',
                    $reflectionException->getCode(),
                    $reflectionException
                );
            }

            $typeError = new TypeError(
                "Response should specify return type 'array'"
            );
            $reflectionReturnType = $reflectionFunction->getReturnType();
            if ($reflectionReturnType instanceof ReflectionNamedType) {
                if ($reflectionReturnType->getName() !== 'array') {
                    throw $typeError;
                }
            } elseif ($reflectionReturnType instanceof ReflectionType) {
                if ((string)$reflectionReturnType !== 'array') {
                    throw $typeError;
                }
            } elseif ($reflectionReturnType === null) {
                trigger_error(
                    'Response should specify its return type',
                    E_USER_WARNING
                );
            }

            $reflectionParameters = $reflectionFunction->getParameters();
            if (count($reflectionParameters) !== 1) {
                throw new InvalidArgumentException(
                    'Response should specify only one parameter'
                );
            }
            $reflectionParameter = $reflectionParameters[0];
            assert($reflectionParameter instanceof ReflectionParameter);

            $typeError = new TypeError(
                "Response should specify parameter type 'array'"
            );
            $reflectionParameterType = $reflectionParameter->getType();
            if ($reflectionParameterType instanceof ReflectionNamedType) {
                if ($reflectionParameterType->getName() !== 'array') {
                    throw $typeError;
                }
            } elseif ($reflectionParameterType instanceof ReflectionType) {
                if ((string)$reflectionParameterType !== 'array') {
                    throw $typeError;
                }
            } elseif ($reflectionParameterType === null) {
                trigger_error(
                    'Response should specify its parameter type',
                    E_USER_WARNING
                );
            }
        }

        return $response;
    }

    /**
     * getValidation
     *
     * @return array
     */
    public function getValidation(): ?array
    {
        return $this->validation;
    }

    /**
     * setValidation
     *
     * @param array|null $validation
     *
     * @return $this
     */
    public function setValidation(?array $validation): self
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * getOptions
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * setOptions
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }
}
