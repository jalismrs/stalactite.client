<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * DomainModel
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 */
class DomainModel extends
    ModelAbstract
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
     * @return DomainModel
     */
    public function setName(?string $name) : DomainModel
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
     * @return DomainModel
     */
    public function setType(?string $type) : DomainModel
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
     * @return DomainModel
     */
    public function setApiKey(?string $apiKey) : DomainModel
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
     * @return DomainModel
     */
    public function setExternalAuth(bool $externalAuth) : DomainModel
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
     * @return DomainModel
     */
    public function setGenerationDate(?string $generationDate) : DomainModel
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
