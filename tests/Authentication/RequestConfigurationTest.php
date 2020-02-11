<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Service;
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
 * @package Jalismrs\Stalactite\Client\Tests\Authentication
 */
class RequestConfigurationTest extends
    TestCase
{
    use RequestConfigurationTestTrait;
    
    /**
     * testLogin
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
    public function testLogin() : void
    {
        $mockClient  = new Client('http://fakeHost');
        $mockService = new Service($mockClient);
        
        self::checkRequestConfigration(
            $mockService,
            'LOGIN'
        );
    }
}
