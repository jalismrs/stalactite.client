<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\RequestConfiguration\Data\Domain;

use Jalismrs\Stalactite\Client\Data\Domain\Service;
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
 * @package Jalismrs\Stalactite\Client\Tests\Data\Domain
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
            'create'
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
            'delete'
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
            'getAll'
        );
    }
    
    /**
     * testGetByNameAndApiKey
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
    public function testGetByNameAndApiKey() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        self::checkRequestConfigration(
            $mockService,
            'getByNameAndApiKey'
        );
    }
    
    /**
     * testGetByName
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
    public function testGetByName() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        self::checkRequestConfigration(
            $mockService,
            'getByName'
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
            'get'
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
            'update'
        );
    }
}
