<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Model;

/**
 * Trait ModelNameTrait
 *
 * @package Jalismrs\Stalactite\Client\Data\Model
 */
trait ModelNameTrait
{
    /**
     * lastName
     *
     * @var string|null
     */
    private ?string $lastName = null;
    /**
     * firstName
     *
     * @var string|null
     */
    private ?string $firstName = null;
    
    /**
     * getNameLF
     *
     * @return string
     */
    public function getNameLF() : string
    {
        return self::getName(
            $this->getLastName(),
            $this->getFirstName(),
        );
    }
    
    /**
     * getName
     *
     * @static
     *
     * @param string ...$parts
     *
     * @return string
     */
    private static function getName(
        ?string ...$parts
    ) : string {
        $name = implode(
            ' ',
            $parts
        );
        
        return trim($name);
    }
    
    /**
     * getLastName
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getLastName() : ?string
    {
        return $this->lastName;
    }
    
    /**
     * setLastName
     *
     * @param string|null $lastName
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setLastName(?string $lastName) : self
    {
        $this->lastName = $lastName;
        
        return $this;
    }
    
    /**
     * getFirstName
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getFirstName() : ?string
    {
        return $this->firstName;
    }
    
    /**
     * setFirstName
     *
     * @param string|null $firstName
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setFirstName(?string $firstName) : self
    {
        $this->firstName = $firstName;
        
        return $this;
    }
    
    /**
     * getNameFL
     *
     * @return string
     */
    public function getNameFL() : string
    {
        return self::getName(
            $this->getFirstName(),
            $this->getLastName(),
        );
    }
}
