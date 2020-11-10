<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

/**
 * Class ModelNameTraitProvider
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 */
class ModelNameTraitProvider
{
    private const FIRST_NAME = 'John';
    private const LAST_NAME  = 'DOE';
    
    /**
     * provideGetNameFL
     *
     * @return array[]
     */
    public function provideGetNameFL() : array
    {
        return [
            'null-null'   => [
                'input'  => [
                    'firstName' => null,
                    'lastName'  => null,
                ],
                'output' => '',
            ],
            'empty-empty' => [
                'input'  => [
                    'firstName' => '',
                    'lastName'  => '',
                ],
                'output' => '',
            ],
            'first-null'  => [
                'input'  => [
                    'firstName' => self::FIRST_NAME,
                    'lastName'  => null,
                ],
                'output' => self::FIRST_NAME,
            ],
            'first-empty' => [
                'input'  => [
                    'firstName' => self::FIRST_NAME,
                    'lastName'  => '',
                ],
                'output' => self::FIRST_NAME,
            ],
            'null-last'   => [
                'input'  => [
                    'firstName' => null,
                    'lastName'  => self::LAST_NAME,
                ],
                'output' => self::LAST_NAME,
            ],
            'empty-last'  => [
                'input'  => [
                    'firstName' => '',
                    'lastName'  => self::LAST_NAME,
                ],
                'output' => self::LAST_NAME,
            ],
            'first-last'  => [
                'input'  => [
                    'firstName' => self::FIRST_NAME,
                    'lastName'  => self::LAST_NAME,
                ],
                'output' => self::FIRST_NAME . ' ' . self::LAST_NAME,
            ],
        ];
    }
    
    /**
     * provideGetNameLF
     *
     * @return array[]
     */
    public function provideGetNameLF() : array
    {
        return [
            'null-null'   => [
                'input'  => [
                    'firstName' => null,
                    'lastName'  => null,
                ],
                'output' => '',
            ],
            'empty-empty' => [
                'input'  => [
                    'firstName' => '',
                    'lastName'  => '',
                ],
                'output' => '',
            ],
            'first-null'  => [
                'input'  => [
                    'firstName' => self::FIRST_NAME,
                    'lastName'  => null,
                ],
                'output' => self::FIRST_NAME,
            ],
            'first-empty' => [
                'input'  => [
                    'firstName' => self::FIRST_NAME,
                    'lastName'  => '',
                ],
                'output' => self::FIRST_NAME,
            ],
            'null-last'   => [
                'input'  => [
                    'firstName' => null,
                    'lastName'  => self::LAST_NAME,
                ],
                'output' => self::LAST_NAME,
            ],
            'empty-last'  => [
                'input'  => [
                    'firstName' => '',
                    'lastName'  => self::LAST_NAME,
                ],
                'output' => self::LAST_NAME,
            ],
            'first-last'  => [
                'input'  => [
                    'firstName' => self::FIRST_NAME,
                    'lastName'  => self::LAST_NAME,
                ],
                'output' => self::LAST_NAME . ' ' . self::FIRST_NAME,
            ],
        ];
    }
}
