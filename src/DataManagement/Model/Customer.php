<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Customer
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 */
class Customer extends
    AbstractModel
{
    /** @var null|string $googleId */
    private $googleId;
    
    /** @var null|string $email */
    private $email;
    
    /** @var null|string $firstName */
    private $firstName;
    
    /** @var null|string $lastName */
    private $lastName;
    
    /**
     * @return string|null
     */
    public function getGoogleId() : ?string
    {
        return $this->googleId;
    }
    
    /**
     * @param string|null $googleId
     *
     * @return Customer
     */
    public function setGoogleId(?string $googleId) : Customer
    {
        $this->googleId = $googleId;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }
    
    /**
     * @param string|null $email
     *
     * @return Customer
     */
    public function setEmail(?string $email) : Customer
    {
        $this->email = $email;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getFirstName() : ?string
    {
        return $this->firstName;
    }
    
    /**
     * @param string|null $firstName
     *
     * @return Customer
     */
    public function setFirstName(?string $firstName) : Customer
    {
        $this->firstName = $firstName;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getLastName() : ?string
    {
        return $this->lastName;
    }
    
    /**
     * @param string|null $lastName
     *
     * @return Customer
     */
    public function setLastName(?string $lastName) : Customer
    {
        $this->lastName = $lastName;
        
        return $this;
    }
    
    /**
     * @return array
     * Return the object as an array
     */
    public function asArray() : array
    {
        return [
            'uid'       => $this->uid,
            'googleId'  => $this->googleId,
            'email'     => $this->email,
            'firstName' => $this->firstName,
            'lastName'  => $this->lastName
        ];
    }
}
