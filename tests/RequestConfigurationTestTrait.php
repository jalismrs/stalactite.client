<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests;

use Jalismrs\Stalactite\Client\AbstractService;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Validation;
use function assert;
use function get_class;
use function json_encode;
use function vsprintf;

/**
 * RequestConfigurationTestTrait
 *
 * @package Jalismrs\Stalactite\Client\Tests
 * @mixin TestCase
 */
trait RequestConfigurationTestTrait
{
    /**
     * checkRequestConfigration
     *
     * @static
     *
     * @param AbstractService $mockService
     * @param string          $name
     *
     * @return void
     *
     * @throws ConstraintDefinitionException
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     * @throws ReflectionException
     */
    private static function checkRequestConfigration(
        AbstractService $mockService,
        string $name
    ) : void {
        $reflectionClass = new ReflectionClass($mockService);
        
        $reflectionProperty = $reflectionClass->getProperty('requestConfigurations');
        $reflectionProperty->setAccessible(true);
        
        $requestConfigurations = $reflectionProperty->getValue($mockService);
        
        self::assertArrayHasKey(
            $name,
            $requestConfigurations,
            vsprintf(
                "RequestConfiguration '%s' not found in service %s",
                [
                    $name,
                    get_class($mockService)
                ]
            )
        );
        
        $requestConfiguration = $requestConfigurations[$name];
        
        $validator = Validation::createValidator();
        
        $errors = $validator->validate(
            $requestConfiguration,
            new Constraints\Collection(
                [
                    'fields' => [
                        'endpoint'      => new Constraints\Required(
                            [
                                'constraints' => [
                                    new Constraints\Type(
                                        [
                                            'type' => 'string',
                                        ]
                                    ),
                                ],
                            ]
                        ),
                        'method'        => new Constraints\Required(
                            [
                                'constraints' => [
                                    new Constraints\Choice(
                                        [
                                            'choices' => [
                                                'DELETE',
                                                'GET',
                                                'POST',
                                                'PUT',
                                            ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                        'validation'    => new Constraints\Optional(
                            [
                                'constraints' => [
                                    new Constraints\Type('array'),
                                ],
                            ]
                        ),
                        'normalization' => new Constraints\Optional(
                            [
                                'constraints' => [
                                    new Constraints\Collection(
                                        [
                                            'fields' => [
                                                AbstractNormalizer::GROUPS             => new Constraints\Optional(
                                                    [
                                                        'constraints' => [
                                                            new Constraints\All(
                                                                [
                                                                    'constraints' => [
                                                                        new Constraints\Type(
                                                                            [
                                                                                'type' => 'string',
                                                                            ]
                                                                        ),
                                                                    ],
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                                AbstractNormalizer::IGNORED_ATTRIBUTES => new Constraints\Optional(
                                                    [
                                                        'constraints' => [
                                                            new Constraints\All(
                                                                [
                                                                    'constraints' => [
                                                                        new Constraints\Type(
                                                                            [
                                                                                'type' => 'string',
                                                                            ]
                                                                        ),
                                                                    ],
                                                                ]
                                                            ),
                                                        ],
                                                    ]
                                                ),
                                            ],
                                        ]
                                    ),
                                ],
                            ]
                        ),
                    ],
                ]
            )
        );
        
        self::assertEmpty(
            $errors,
            self::getErrorMessage($errors)
        );
    }
    
    /**
     * getErrorMessage
     *
     * @static
     *
     * @param ConstraintViolationListInterface $errors
     *
     * @return string
     */
    private static function getErrorMessage(ConstraintViolationListInterface $errors) : string
    {
        $messages = [];
        
        foreach ($errors as $error) {
            assert($error instanceof ConstraintViolationInterface);
            
            $messages[] = [
                'message'  => $error->getMessage(),
                'property' => $error->getPropertyPath(),
                'value'    => $error->getInvalidValue(),
            ];
        }
        
        return json_encode(
            $messages,
            JSON_THROW_ON_ERROR
        );
    }
}
