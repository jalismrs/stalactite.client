<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * TrustedAppModel
 *
 * @package Jalismrs\Stalactite\Client\Authentication\Model
 */
class TrustedAppModel extends
    ModelAbstract
{
    /** @var null|string $name */
    private $name;
    
    /** @var null|string $googleOAuthClientId */
    private $googleOAuthClientId;
    
    /** @var null|string $authToken */
    private $authToken;
    
    /** @var null|string $resetToken */
    private $resetToken;
    
    /**
     * @return string
     */
    public function getName() : ?string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     *
     * @return TrustedAppModel
     */
    public function setName(?string $name) : TrustedAppModel
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getGoogleOAuthClientId() : ?string
    {
        return $this->googleOAuthClientId;
    }
    
    /**
     * @param string $googleOAuthClientId
     *
     * @return TrustedAppModel
     */
    public function setGoogleOAuthClientId(?string $googleOAuthClientId) : TrustedAppModel
    {
        $this->googleOAuthClientId = $googleOAuthClientId;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAuthToken() : ?string
    {
        return $this->authToken;
    }
    
    /**
     * @param string $authToken
     *
     * @return TrustedAppModel
     */
    public function setAuthToken(?string $authToken) : TrustedAppModel
    {
        $this->authToken = $authToken;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getResetToken() : ?string
    {
        return $this->resetToken;
    }
    
    /**
     * @param string $resetToken
     *
     * @return TrustedAppModel
     */
    public function setResetToken(?string $resetToken) : TrustedAppModel
    {
        $this->resetToken = $resetToken;
        
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
            'uid'                 => $this->uid,
            'name'                => $this->name,
            'authToken'           => $this->authToken,
            'googleOAuthClientId' => $this->googleOAuthClientId,
        ];
    }
}
