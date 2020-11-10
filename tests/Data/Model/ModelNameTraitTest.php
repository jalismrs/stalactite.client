<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Data\Model\ModelNameTrait;
use PHPUnit\Framework\TestCase;

/**
 * ModelNameTraitTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 *
 * @covers  \Jalismrs\Stalactite\Client\Data\Model\ModelNameTrait
 */
class ModelNameTraitTest extends
    TestCase
{
    /**
     * testGetNameFL
     *
     * @param array  $providedInput
     * @param string $providedOutput
     *
     * @return void
     *
     * @dataProvider \Jalismrs\Stalactite\Client\Tests\Data\Model\ModelNameTraitProvider::provideGetNameFL
     */
    public function testGetNameFL(
        array $providedInput,
        string $providedOutput
    ) : void
    {
        // arrange
        /**
         * @var ModelNameTrait $systemUnderTest
         */
        $systemUnderTest = $this->createSUT();
    
        $systemUnderTest->setFirstName($providedInput['firstName']);
        $systemUnderTest->setLastName($providedInput['lastName']);
    
        // act
        $output = $systemUnderTest->getNameFL();
        
        // assert
        self::assertSame(
            $providedOutput,
            $output
        );
    }
    
    /**
     * createSUT
     *
     * @return object
     */
    private function createSUT() : object
    {
        return new class() {
            use ModelNameTrait;
        };
    }
    
    /**
     * testGetNameLF
     *
     * @param array  $providedInput
     * @param string $providedOutput
     *
     * @return void
     *
     * @dataProvider \Jalismrs\Stalactite\Client\Tests\Data\Model\ModelNameTraitProvider::provideGetNameLF
     */
    public function testGetNameLF(
        array $providedInput,
        string $providedOutput
    ) : void
    {
        // arrange
        /**
         * @var ModelNameTrait $systemUnderTest
         */
        $systemUnderTest = $this->createSUT();
        
        $systemUnderTest->setFirstName($providedInput['firstName']);
        $systemUnderTest->setLastName($providedInput['lastName']);
        
        // act
        $output = $systemUnderTest->getNameLF();
        
        // assert
        self::assertSame(
            $providedOutput,
            $output
        );
    }
}
