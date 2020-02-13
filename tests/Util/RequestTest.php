<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Util;

use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Util\Request;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * RequestTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Util
 */
class RequestTest extends
    TestCase
{
    /**
     * testEndpoint
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testEndpoint() : void
    {
        $endpoint = '/collection1/%s/collection2/%s/details';
        $request  = new Request($endpoint);
        
        self::assertSame(
            $endpoint,
            $request->getEndpoint()
        );
    }
    
    /**
     * testUri
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testUri() : void
    {
        $endpoint      = '/collection1/%s/collection2/%s/details';
        $uriParameters = [
            'test',
            '51',
        ];
        
        $request = new Request($endpoint);
        $request->setUriParameters($uriParameters);
        
        self::assertSame(
            vsprintf(
                $endpoint,
                $uriParameters
            ),
            $request->getUri()
        );
    }
    
    /**
     * testMethod
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testMethod() : void
    {
        $method = 'POST';
        
        $request = new Request(
            '',
            $method
        );
        
        self::assertSame(
            $method,
            $request->getMethod()
        );
    }
    
    /**
     * testMethodDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testMethodDefault() : void
    {
        $request = new Request('');
        
        self::assertSame(
            'GET',
            $request->getMethod()
        );
    }
    
    /**
     * testMethodInvalid
     *
     * @return void
     *
     * @throws RequestException
     */
    public function testMethodInvalid() : void
    {
        $this->expectException(RequestException::class);
        
        new Request(
            '',
            'PSOT'
        );
    }
    
    /**
     * testMethodLowerCase
     *
     * @return void
     *
     * @throws RequestException
     */
    public function testMethodLowerCase() : void
    {
        $this->expectError();
        
        new Request(
            '',
            'put'
        );
    }
    
    /**
     * testJson
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testJson() : void
    {
        $json = [
            AbstractNormalizer::GROUPS => [
                'create',
            ],
        ];
        
        $request = new Request('');
        $request->setJson($json);
        
        self::assertSame(
            $json,
            $request->getJson()
        );
    }
    
    /**
     * testJsonNull
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testJsonNull() : void
    {
        $request = new Request('');
        $request->setJson(null);
        
        self::assertNull($request->getJson());
    }
    
    /**
     * testJsonDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testJsonDefault() : void
    {
        $request = new Request('');
        
        self::assertNull($request->getJson());
    }
    
    /**
     * testJwt
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testJwt() : void
    {
        $jwt = 'fake jwt';
        
        $request = new Request('');
        $request->setJwt($jwt);
        
        self::assertSame(
            $jwt,
            $request->getJwt()
        );
    }
    
    /**
     * testJwtNull
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testJwtNull() : void
    {
        $request = new Request('');
        $request->setJwt(null);
        
        self::assertNull($request->getJwt());
    }
    
    /**
     * testJwtDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testJwtDefault() : void
    {
        $request = new Request('');
        
        self::assertNull($request->getJwt());
    }
    
    /**
     * testQueryParameters
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testQueryParameters() : void
    {
        $queryParameters = [
            AbstractNormalizer::GROUPS => [
                'create',
            ],
        ];
        
        $request = new Request('');
        $request->setQueryParameters($queryParameters);
        
        self::assertSame(
            $queryParameters,
            $request->getQueryParameters()
        );
    }
    
    /**
     * testQueryParametersNull
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testQueryParametersNull() : void
    {
        $request = new Request('');
        $request->setQueryParameters(null);
        
        self::assertNull($request->getQueryParameters());
    }
    
    /**
     * testQueryParametersDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testQueryParametersDefault() : void
    {
        $request = new Request('');
        
        self::assertNull($request->getQueryParameters());
    }
    
    /**
     * testNormalization
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testNormalization() : void
    {
        $normalization = [
            AbstractNormalizer::GROUPS => [
                'create',
            ],
        ];
        
        $request = new Request('');
        $request->setNormalization($normalization);
        
        self::assertSame(
            $normalization,
            $request->getNormalization()
        );
    }
    
    /**
     * testNormalizationNull
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testNormalizationNull() : void
    {
        $request = new Request('');
        $request->setNormalization(null);
        
        self::assertNull($request->getNormalization());
    }
    
    /**
     * testNormalizationDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testNormalizationDefault() : void
    {
        $request = new Request('');
        
        self::assertNull($request->getNormalization());
    }
    
    /**
     * testOptions
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testOptions() : void
    {
        $options = [
            'plop' => 'prout',
        ];
        $request = new Request('');
        $request->setOptions($options);
        
        self::assertSame(
            $options,
            $request->getOptions()
        );
    }
    
    /**
     * testOptionsDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testOptionsDefault() : void
    {
        $request = new Request('');
        
        self::assertEmpty($request->getOptions());
    }
    
    /**
     * testResponse
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testResponse() : void
    {
        $response = static function(array $response) : array {
            return $response;
        };
        
        $request = new Request('');
        $request->setResponse($response);
        
        self::assertSame(
            $response,
            $request->getResponse()
        );
    }
    
    /**
     * testResponseNull
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testResponseNull() : void
    {
        $request = new Request('');
        $request->setResponse(null);
        
        self::assertNull($request->getResponse());
    }
    
    /**
     * testResponseDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testResponseDefault() : void
    {
        $request = new Request('');
        
        self::assertNull($request->getResponse());
    }
    
    /**
     * testResponseMissingReturnType
     *
     * @return void
     *
     * @throws RequestException
     */
    public function testResponseMissingReturnType() : void
    {
        $this->expectError();
        
        $request = new Request('');
        $request->setResponse(
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
     * @throws RequestException
     */
    public function testResponseInvalidReturnType() : void
    {
        $this->expectException(RequestException::class);
        
        $request = new Request('');
        $request->setResponse(
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
     * @throws RequestException
     */
    public function testResponseInvalidParameterCount() : void
    {
        $this->expectException(RequestException::class);
        
        $request = new Request('');
        $request->setResponse(
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
     * @throws RequestException
     */
    public function testResponseMissingParameterType() : void
    {
        $this->expectError();
        
        $request = new Request('');
        $request->setResponse(
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
     * @throws RequestException
     */
    public function testResponseInvalidParameterType() : void
    {
        $this->expectException(RequestException::class);
        
        $request = new Request('');
        $request->setResponse(
            static function(bool $response) : array {
                return [$response];
            }
        );
    }
    
    /**
     * testUriParameters
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testUriParameters() : void
    {
        $uriParameters = [
            'prout',
        ];
        
        $request = new Request('');
        $request->setUriParameters($uriParameters);
        
        self::assertSame(
            $uriParameters,
            $request->getUriParameters()
        );
    }
    
    /**
     * testUriParametersDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testUriParametersDefault() : void
    {
        $request = new Request('');
        
        self::assertEmpty($request->getUriParameters());
    }
    
    /**
     * testUriParametersInvalidType
     *
     * @return void
     *
     * @throws RequestException
     */
    public function testUriParametersInvalidType() : void
    {
        $this->expectException(RequestException::class);
        
        $request = new Request('');
        $request->setUriParameters(
            [
                'prout',
                []
            ]
        );
    }
    
    /**
     * testUriParametersTypeBool
     *
     * @return void
     *
     * @throws RequestException
     */
    public function testUriParametersTypeBool() : void
    {
        $this->expectError();
        
        $request = new Request('');
        $request->setUriParameters(
            [
                'prout',
                true
            ]
        );
    }
    
    /**
     * testValidation
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testValidation() : void
    {
        $validation = [
            'clearance' => [
                'type' => JsonRule::OBJECT_TYPE,
                'null' => true,
            ]
        ];
        
        $request = new Request('');
        $request->setValidation($validation);
        
        self::assertSame(
            $validation,
            $request->getValidation()
        );
    }
    
    /**
     * testValidationNull
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testValidationNull() : void
    {
        $request = new Request('');
        $request->setValidation(null);
        
        self::assertNull($request->getValidation());
    }
    
    /**
     * testValidationDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testValidationDefault() : void
    {
        $request = new Request('');
        
        self::assertNull($request->getValidation());
    }
}
