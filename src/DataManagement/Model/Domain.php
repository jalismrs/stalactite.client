<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Domain
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 */
class Domain extends
    AbstractModel
{
    /** @var null|string $name */
    private $name;
    
    /** @var null|string $type */
    private $type;
    
    /** @var null|string $apiKey */
    private $apiKey;
    
    /** @var bool $externalAuth */
    private $externalAuth = false;
    
    /** @var null|string $generationDate */
    private $generationDate;
    
    /**
     * @return string|null
     */
    public function getName() : ?string
    {
        return $this->name;
    }
    
    /**
     * @param string|null $name
     *
     * @return Domain
     */
    public function setName(?string $name) : Domain
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getType() : ?string
    {
        return $this->type;
    }
    
    /**
     * @param string|null $type
     *
     * @return Domain
     */
    public function setType(?string $type) : Domain
    {
        $this->type = $type;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getApiKey() : ?string
    {
        return $this->apiKey;
    }
    
    /**
     * @param string|null $apiKey
     *
     * @return Domain
     */
    public function setApiKey(?string $apiKey) : Domain
    {
        $this->apiKey = $apiKey;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasExternalAuth() : bool
    {
        return $this->externalAuth;
    }
    
    /**
     * @param bool $externalAuth
     *
     * @return Domain
     */
    public function setExternalAuth(bool $externalAuth) : Domain
    {
        $this->externalAuth = $externalAuth;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getGenerationDate() : ?string
    {
        return $this->generationDate;
    }
    
    /**
     * @param string|null $generationDate
     *
     * @return Domain
     */
    public function setGenerationDate(?string $generationDate) : Domain
    {
        $this->generationDate = $generationDate;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function asArray() : array
    {
        return [
            'uid'            => $this->uid,
            'name'           => $this->name,
            'type'           => $this->type,
            'apiKey'         => $this->apiKey,
            'externalAuth'   => $this->externalAuth,
            'generationDate' => $this->generationDate
        ];
    }
}
