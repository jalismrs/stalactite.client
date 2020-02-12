<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

use Closure;
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;
use ReflectionException;
use ReflectionFunction;
use function count;
use function in_array;
use function strtoupper;
use function trigger_error;
use function vsprintf;
use const E_USER_WARNING;

/**
 * RequestConfiguration
 *
 * @package Jalismrs\Stalactite\Client
 */
final class RequestConfiguration
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
     * @var null
     */
    private $normalization = null;
    /**
     * @var null
     */
    private $response = null;
    /**
     * @var null
     */
    private $validation = null;
    
    /**
     * RequestConfiguration constructor.
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
     * @param array $uriDatas
     *
     * @return string
     */
    public function getUri(array $uriDatas) : string
    {
        return vsprintf(
            $this->getEndpoint(),
            $uriDatas
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
     * @throws RequestConfigurationException
     */
    public function setMethod(string $method) : self
    {
        self::validateMethod($method);
        
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
     * @throws RequestConfigurationException
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
                throw new RequestConfigurationException(
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
     */
    public function setNormalization(?array $normalization) : self
    {
        if ($normalization !== null) {
            self::validateNormalization($normalization);
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
     * @throws RequestConfigurationException
     */
    public function setResponse(?Closure $response) : self
    {
        if ($response !== null) {
            self::validateResponse($response);
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
     * @throws RequestConfigurationException
     */
    private static function validateResponse(Closure $response) : void
    {
        try {
            $reflectionFunction = new ReflectionFunction($response);
        } catch (ReflectionException $reflectionException) {
            throw new RequestConfigurationException(
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
            throw new RequestConfigurationException(
                "Response should specify return type 'array'"
            );
        }
        
        $reflectionParameters = $reflectionFunction->getParameters();
        if (count($reflectionParameters) !== 1) {
            throw new RequestConfigurationException(
                'Response should specify only one parameter'
            );
        }
        $reflectionParameter = $reflectionParameters[0];
        assert($reflectionParameter instanceof \ReflectionParameter);
        
        $reflectionParameterType = $reflectionParameter->getType();
        if ($reflectionParameterType === null) {
            trigger_error(
                'Response should specify its parameter type',
                E_USER_WARNING
            );
        } elseif ((string)$reflectionParameterType !== 'array') {
            throw new RequestConfigurationException(
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
     */
    public function setValidation(?array $validation) : self
    {
        if ($validation !== null) {
            self::validateValidation($validation);
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
}
