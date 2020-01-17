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
    /**
     * @var null|string
     */
    private $googleId;
    /**
     * @var null|string
     */
    private $email;
    /**
     * @var null|string
     */
    private $firstName;
    /**
     * @var null|string
     */
    private $lastName;
    
    /**
     * getGoogleId
     *
     * @return null|string
     */
    public function getGoogleId() : ?string
    {
        return $this->googleId;
    }
    
    /**
     * setGoogleId
     *
     * @param null|string $googleId
     *
     * @return $this
     */
    public function setGoogleId(?string $googleId) : self
    {
        $this->googleId = $googleId;
        
        return $this;
    }
    
    /**
     * getEmail
     *
     * @return null|string
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }
    
    /**
     * setEmail
     *
     * @param null|string $email
     *
     * @return $this
     */
    public function setEmail(?string $email) : self
    {
        $this->email = $email;
        
        return $this;
    }
    
    /**
     * getFirstName
     *
     * @return null|string
     */
    public function getFirstName() : ?string
    {
        return $this->firstName;
    }
    
    /**
     * setFirstName
     *
     * @param null|string $firstName
     *
     * @return $this
     */
    public function setFirstName(?string $firstName) : self
    {
        $this->firstName = $firstName;
        
        return $this;
    }
    
    /**
     * getLastName
     *
     * @return null|string
     */
    public function getLastName() : ?string
    {
        return $this->lastName;
    }
    
    /**
     * setLastName
     *
     * @param null|string $lastName
     *
     * @return $this
     */
    public function setLastName(?string $lastName) : self
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
