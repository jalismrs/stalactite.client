<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Customer
 *
 * @package Jalismrs\Stalactite\Service\Data\Model
 */
class Customer extends
    AbstractModel
{
    /**
     * googleId
     *
     * @var string|null
     */
    private ?string $googleId = null;
    /**
     * email
     *
     * @var string|null
     */
    private ?string $email = null;
    /**
     * firstName
     *
     * @var string|null
     */
    private ?string $firstName = null;
    /**
     * lastName
     *
     * @var string|null
     */
    private ?string $lastName = null;
    
    /**
     * getSchema
     *
     * @static
     * @return array[]
     *
     * @codeCoverageIgnore
     */
    public static function getSchema() : array
    {
        return [
            'uid'       => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'email'     => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'firstName' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'lastName'  => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'googleId'  => [
                'type' => JsonRule::STRING_TYPE,
                'null' => true,
            ],
        ];
    }
    
    /**
     * getGoogleId
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getGoogleId() : ?string
    {
        return $this->googleId;
    }
    
    /**
     * setGoogleId
     *
     * @param string|null $googleId
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setGoogleId(?string $googleId) : self
    {
        $this->googleId = $googleId;
        
        return $this;
    }
    
    /**
     * getEmail
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }
    
    /**
     * setEmail
     *
     * @param string|null $email
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setEmail(?string $email) : self
    {
        $this->email = $email;
        
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
}
