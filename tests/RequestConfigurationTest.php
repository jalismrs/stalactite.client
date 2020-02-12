<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;
use Jalismrs\Stalactite\Client\RequestConfiguration;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * RequestConfigurationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests
 */
class RequestConfigurationTest extends
    TestCase
{
    /**
     * testEndpoint
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testEndpoint() : void
    {
        $endpoint             = '/collection1/%s/collection2/%s/details';
        $requestConfiguration = new RequestConfiguration($endpoint);
        
        self::assertSame(
            $endpoint,
            $requestConfiguration->getEndpoint()
        );
    }
    
    /**
     * testUri
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testUri() : void
    {
        $endpoint             = '/collection1/%s/collection2/%s/details';
        $requestConfiguration = new RequestConfiguration($endpoint);
        
        self::assertSame(
            '/collection1/test/collection2/51/details',
            $requestConfiguration->getUri(
                [
                    'test',
                    '51',
                ]
            )
        );
    }
    
    /**
     * testMethod
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestConfigurationException
     */
    public function testMethod() : void
    {
        $method = 'POST';
        
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setMethod($method);
        
        self::assertSame(
            $method,
            $requestConfiguration->getMethod()
        );
    }
    
    /**
     * testMethodDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testMethodDefault() : void
    {
        $requestConfiguration = new RequestConfiguration('');
        
        self::assertSame(
            'GET',
            $requestConfiguration->getMethod()
        );
    }
    
    /**
     * testMethodInvalid
     *
     * @return void
     *
     * @throws RequestConfigurationException
     */
    public function testMethodInvalid() : void
    {
        $this->expectException(RequestConfigurationException::class);
        
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setMethod('PSOT');
    }
    
    /**
     * testMethodLowerCase
     *
     * @return void
     *
     * @throws RequestConfigurationException
     */
    public function testMethodLowerCase() : void
    {
        $this->expectError();
        
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setMethod('put');
    }
    
    /**
     * testNormalization
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testNormalization() : void
    {
        $normalization = [
            AbstractNormalizer::GROUPS => [
                'create',
            ],
        ];
        
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setNormalization($normalization);
        
        self::assertSame(
            $normalization,
            $requestConfiguration->getNormalization()
        );
    }
    
    /**
     * testMethod
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testNormalizationNull() : void
    {
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setNormalization(null);
        
        self::assertNull($requestConfiguration->getNormalization());
    }
    
    /**
     * testNormalizationDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testNormalizationDefault() : void
    {
        $requestConfiguration = new RequestConfiguration('');
        
        self::assertNull($requestConfiguration->getNormalization());
    }
    
    /**
     * testResponse
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestConfigurationException
     */
    public function testResponse() : void
    {
        $response = static function(array $response) : array {
            return $response;
        };
        
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setResponse($response);
        
        self::assertSame(
            $response,
            $requestConfiguration->getResponse()
        );
    }
    
    /**
     * testResponseNull
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestConfigurationException
     */
    public function testResponseNull() : void
    {
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setResponse(null);
        
        self::assertNull($requestConfiguration->getResponse());
    }
    
    /**
     * testValidationDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testResponseDefault() : void
    {
        $requestConfiguration = new RequestConfiguration('');
        
        self::assertNull($requestConfiguration->getResponse());
    }
    
    /**
     * testResponseMissingReturnType
     *
     * @return void
     *
     * @throws RequestConfigurationException
     */
    public function testResponseMissingReturnType() : void
    {
        $this->expectError();
        
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setResponse(
            static function(array $response) {
                return $response;
            }
        );
    }
    
    /**
     * testResponseInvalidReturnType
     *
     * @return void
     *
     * @throws RequestConfigurationException
     */
    public function testResponseInvalidReturnType() : void
    {
        $this->expectException(RequestConfigurationException::class);
        
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setResponse(
            static function(array $response) : bool {
                return $response === [];
            }
        );
    }
    
    /**
     * testResponseInvalidParameterCount
     *
     * @return void
     *
     * @throws RequestConfigurationException
     */
    public function testResponseInvalidParameterCount() : void
    {
        $this->expectException(RequestConfigurationException::class);
        
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setResponse(
            static function(array $response, array $invalid) : array {
                return array_merge($response, $invalid);
            }
        );
    }
    
    /**
     * testResponseMissingParameterType
     *
     * @return void
     *
     * @throws RequestConfigurationException
     */
    public function testResponseMissingParameterType() : void
    {
        $this->expectError();
        
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setResponse(
            static function($response) : array {
                return $response;
            }
        );
    }
    
    /**
     * testResponseInvalidParameterType
     *
     * @return void
     *
     * @throws RequestConfigurationException
     */
    public function testResponseInvalidParameterType() : void
    {
        $this->expectException(RequestConfigurationException::class);
        
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setResponse(
            static function(bool $response) : array {
                return [$response];
            }
        );
    }
    
    /**
     * testMethod
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testValidation() : void
    {
        $validation = [
            'clearance' => [
                'type' => JsonRule::OBJECT_TYPE,
                'null' => true,
            ]
        ];
        
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setValidation($validation);
        
        self::assertSame(
            $validation,
            $requestConfiguration->getValidation()
        );
    }
    
    /**
     * testMethod
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testValidationNull() : void
    {
        $requestConfiguration = new RequestConfiguration('');
        $requestConfiguration->setValidation(null);
        
        self::assertNull($requestConfiguration->getValidation());
    }
    
    /**
     * testValidationDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testValidationDefault() : void
    {
        $requestConfiguration = new RequestConfiguration('');
        
        self::assertNull($requestConfiguration->getValidation());
    }
}
