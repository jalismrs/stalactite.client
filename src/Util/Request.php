<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Util;

use Closure;
use ErrorException;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use OutOfBoundsException;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;
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
final class Request
{
    /**
     * @var string
     */
    private $endpoint;
    /**
     * @var string
     */
    private $method = 'GET';
    /**
     * @var array|null
     */
    private $normalization;
    /**
     * @var array
     */
    private $options = [];
    /**
     * @var Closure|null
     */
    private $response;
    /**
     * @var array
     */
    private $uriDatas = [];
    /**
     * @var array|null
     */
    private $validation;
    
    /**
     * Request constructor.
     *
     * @param string $endpoint
     */
    public function __construct(
        string $endpoint
    ) {
        $this->endpoint = $endpoint;
    }
    
    /**
     * getUri
     *
     * @return string
     */
    public function getUri() : string
    {
        return vsprintf(
            $this->getEndpoint(),
            $this->getUriDatas()
        );
    }
    
    /**
     * getEndpoint
     *
     * @return string
     */
    public function getEndpoint() : string
    {
        return $this->endpoint;
    }
    
    /**
     * getMethod
     *
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }
    
    /**
     * setMethod
     *
     * @param string $method
     *
     * @return $this
     *
     * @throws RequestException
     */
    public function setMethod(string $method) : self
    {
        try {
            self::validateMethod($method);
            
            $throwable = null;
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
        
        $this->method = strtoupper($method);
        
        return $this;
    }
    
    /**
     * validateMethod
     *
     * @static
     *
     * @param string $method
     *
     * @return void
     *
     * @throws OutOfBoundsException
     */
    private static function validateMethod(string $method) : void
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
        }
    }
    
    /**
     * getNormalization
     *
     * @return array
     */
    public function getNormalization() : ?array
    {
        return $this->normalization;
    }
    
    /**
     * setNormalization
     *
     * @param array|null $normalization
     *
     * @return $this
     *
     * @throws RequestException
     */
    public function setNormalization(?array $normalization) : self
    {
        if ($normalization !== null) {
            try {
                self::validateNormalization($normalization);
                
                $throwable = null;
            } finally {
                if ($throwable instanceof Throwable) {
                    throw new RequestException(
                        'error while setting normalization',
                        $throwable->getCode(),
                        $throwable
                    );
                }
            }
        }
        
        $this->normalization = $normalization;
        
        return $this;
    }
    
    /**
     * validateNormalization
     *
     * @param array $normlization
     *
     * @return void
     */
    private static function validateNormalization(array $normlization) : void
    {
    
    }
    
    /**
     * getResponse
     *
     * @return Closure|null
     */
    public function getResponse() : ?Closure
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
    public function setResponse(?Closure $response) : self
    {
        if ($response !== null) {
            try {
                self::validateResponse($response);
                
                $throwable = null;
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
        }
        
        $this->response = $response;
        
        return $this;
    }
    
    /**
     * validateResponse
     *
     * @static
     *
     * @param Closure $response
     *
     * @return void
     *
     * @throws ErrorException
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    private static function validateResponse(Closure $response) : void
    {
        try {
            $reflectionFunction = new ReflectionFunction($response);
        } catch (ReflectionException $reflectionException) {
            throw new ErrorException(
                'should never happen',
                $reflectionException->getCode(),
                $reflectionException
            );
        }
        
        $reflectionReturnType = $reflectionFunction->getReturnType();
        if ($reflectionReturnType === null) {
            trigger_error(
                'Response should specify its return type',
                E_USER_WARNING
            );
        } elseif ((string)$reflectionReturnType !== 'array') {
            throw new TypeError(
                "Response should specify return type 'array'"
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
        
        $reflectionParameterType = $reflectionParameter->getType();
        if ($reflectionParameterType === null) {
            trigger_error(
                'Response should specify its parameter type',
                E_USER_WARNING
            );
        } elseif ((string)$reflectionParameterType !== 'array') {
            throw new TypeError(
                "Response should specify parameter type 'array'"
            );
        }
    }
    
    /**
     * getValidation
     *
     * @return array
     */
    public function getValidation() : ?array
    {
        return $this->validation;
    }
    
    /**
     * setValidation
     *
     * @param array|null $validation
     *
     * @return $this
     *
     * @throws RequestException
     */
    public function setValidation(?array $validation) : self
    {
        if ($validation !== null) {
            try {
                self::validateValidation($validation);
                
                $throwable = null;
            } finally {
                if ($throwable instanceof Throwable) {
                    throw new RequestException(
                        'error while setting validation',
                        $throwable->getCode(),
                        $throwable
                    );
                }
            }
        }
        
        $this->validation = $validation;
        
        return $this;
    }
    
    /**
     * validateValidation
     *
     * @static
     *
     * @param array $validation
     *
     * @return void
     */
    private static function validateValidation(array $validation) : void
    {
    
    }
    
    /**
     * getUriDatas
     *
     * @return array
     */
    public function getUriDatas() : array
    {
        return $this->uriDatas;
    }
    
    /**
     * setUriDatas
     *
     * @param array $uriDatas
     *
     * @return $this
     *
     * @throws RequestException
     */
    public function setUriDatas(array $uriDatas) : self
    {
        try {
            self::validateUriDatas($uriDatas);
            
            $throwable = null;
        } catch (TypeError $typeError) {
            $throwable = $typeError;
        } finally {
            if ($throwable instanceof Throwable) {
                throw new RequestException(
                    'error while setting uriDatas',
                    $throwable->getCode(),
                    $throwable
                );
            }
        }
        
        $this->uriDatas = $uriDatas;
        
        return $this;
    }
    
    /**
     * validateUriDatas
     *
     * @static
     *
     * @param array $uriDatas
     *
     * @return void
     *
     * @throws TypeError
     */
    private static function validateUriDatas(array $uriDatas) : void
    {
        foreach ($uriDatas as $uriData) {
            if (!is_scalar($uriData)) {
                $type = gettype($uriData);
                throw new TypeError(
                    "Expected a scalar value, received '{$type}'"
                );
            }
            if (is_bool($uriData)) {
                trigger_error(
                    'boolean value in discouraged, prefer int',
                    E_USER_WARNING
                );
            }
        }
    }
    
    /**
     * getOptions
     *
     * @return array
     */
    public function getOptions() : array
    {
        return $this->options;
    }
    
    /**
     * setOptions
     *
     * @param array $options
     *
     * @return $this
     *
     * @throws RequestException
     */
    public function setOptions(array $options) : self
    {
        try {
            self::validateOptions($options);
            
            $throwable = null;
        } finally {
            if ($throwable instanceof Throwable) {
                throw new RequestException(
                    'error while setting options',
                    $throwable->getCode(),
                    $throwable
                );
            }
        }
        
        $this->options = $options;
        
        return $this;
    }
    
    /**
     * validateOptions
     *
     * @static
     *
     * @param array $options
     *
     * @return void
     */
    private static function validateOptions(array $options) : void
    {
    
    }
}
