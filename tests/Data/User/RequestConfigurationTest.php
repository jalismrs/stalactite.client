<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\User;

use Jalismrs\Stalactite\Client\Data\User\Service;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\Tests\RequestConfigurationTestTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * RequestConfigurationTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\User
 */
class RequestConfigurationTest extends
    TestCase
{
    use RequestConfigurationTestTrait;
    
    /**
     * testCreate
     *
     * @return void
     *
     * @throws ConstraintDefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     * @throws ReflectionException
     */
    public function testCreate() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        self::checkRequestConfigration(
            $mockService,
            'CREATE'
        );
    }
    
    /**
     * testDelete
     *
     * @return void
     *
     * @throws ConstraintDefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     * @throws ReflectionException
     */
    public function testDelete() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        self::checkRequestConfigration(
            $mockService,
            'DELETE'
        );
    }
    
    /**
     * testGetAll
     *
     * @return void
     *
     * @throws ConstraintDefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     * @throws ReflectionException
     */
    public function testGetAll() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        self::checkRequestConfigration(
            $mockService,
            'GET_ALL'
        );
    }
    
    /**
     * testGetByEmailAndGoogleId
     *
     * @return void
     *
     * @throws ConstraintDefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     * @throws ReflectionException
     */
    public function testGetByEmailAndGoogleId() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        self::checkRequestConfigration(
            $mockService,
            'GET_BY_EMAIL_AND_GOOGLE_ID'
        );
    }
    
    /**
     * testGet
     *
     * @return void
     *
     * @throws ConstraintDefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     * @throws ReflectionException
     */
    public function testGet() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        self::checkRequestConfigration(
            $mockService,
            'GET'
        );
    }
    
    /**
     * testUpdate
     *
     * @return void
     *
     * @throws ConstraintDefinitionException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     * @throws ReflectionException
     */
    public function testUpdate() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        self::checkRequestConfigration(
            $mockService,
            'UPDATE'
        );
    }
}
