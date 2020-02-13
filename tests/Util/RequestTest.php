<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Util;

use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Request;
use PHPUnit\Framework\Exception;
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
        $endpoint = '/collection1/%s/collection2/%s/details';
        $uriDatas = [
            'test',
            '51',
        ];
        
        $request = new Request($endpoint);
        $request->setUriDatas($uriDatas);
        
        self::assertSame(
            vsprintf(
                $endpoint,
                $uriDatas
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
        
        $request = new Request('');
        $request->setMethod($method);
        
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
        
        $request = new Request('');
        $request->setMethod('PSOT');
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
        
        $request = new Request('');
        $request->setMethod('put');
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
     * @throws SerializerException
     */
    public function testOptions() : void
    {
        $options = [
            'query' => [],
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
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
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
     * testValidationDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
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
     * testUriDatas
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RequestException
     */
    public function testUriDatas() : void
    {
        $uriDatas = [
            'prout',
        ];
        
        $request = new Request('');
        $request->setUriDatas($uriDatas);
        
        self::assertSame(
            $uriDatas,
            $request->getUriDatas()
        );
    }
    
    /**
     * testUriDatasDefault
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testUriDatasDefault() : void
    {
        $request = new Request('');
        
        self::assertEmpty($request->getUriDatas());
    }
    
    /**
     * testUriDatasInvalidType
     *
     * @return void
     *
     * @throws RequestException
     */
    public function testUriDatasInvalidType() : void
    {
        $this->expectException(RequestException::class);
        
        $request = new Request('');
        $request->setUriDatas(
            [
                'prout',
                []
            ]
        );
    }
    
    /**
     * testUriDatasTypeBool
     *
     * @return void
     *
     * @throws RequestException
     */
    public function testUriDatasTypeBool() : void
    {
        $this->expectError();
        
        $request = new Request('');
        $request->setUriDatas(
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
     */
    public function testValidationDefault() : void
    {
        $request = new Request('');
        
        self::assertNull($request->getValidation());
    }
}
