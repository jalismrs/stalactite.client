<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * CustomerModel
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 */
class CustomerModel extends
    ModelAbstract
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
     * @return CustomerModel
     */
    public function setGoogleId(?string $googleId) : CustomerModel
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
     * @return CustomerModel
     */
    public function setEmail(?string $email) : CustomerModel
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
     * @return CustomerModel
     */
    public function setFirstName(?string $firstName) : CustomerModel
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
     * @return CustomerModel
     */
    public function setLastName(?string $lastName) : CustomerModel
    {
        $this->lastName = $lastName;
        
        return $this;
    }
    
    /**
     * asArray
     *
     * @return array
     */
    public function asArray() : array
    {
        return [
            'uid'       => $this->uid,
            'googleId'  => $this->googleId,
            'email'     => $this->email,
            'firstName' => $this->firstName,
            'lastName'  => $this->lastName,
        ];
    }
}
