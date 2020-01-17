<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * PostModel
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 */
class PostModel extends
    ModelAbstract
{
    /** @var null|string $name */
    private $name;
    
    /** @var null|string $shortName */
    private $shortName;
    
    /** @var bool $allowAccess */
    private $allowAccess = false;
    
    /** @var bool $adminAccess */
    private $adminAccess = false;
    
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
     * @return PostModel
     */
    public function setName(?string $name) : PostModel
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getShortName() : ?string
    {
        return $this->shortName;
    }
    
    /**
     * @param string|null $shortName
     *
     * @return PostModel
     */
    public function setShortName(?string $shortName) : PostModel
    {
        $this->shortName = $shortName;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function allowAccess() : bool
    {
        return $this->allowAccess;
    }
    
    /**
     * @param bool $allowAccess
     *
     * @return PostModel
     */
    public function setAccess(bool $allowAccess) : PostModel
    {
        $this->allowAccess = $allowAccess;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasAdminAccess() : bool
    {
        return $this->adminAccess;
    }
    
    /**
     * @param bool $adminAccess
     *
     * @return PostModel
     */
    public function setAdminAccess(bool $adminAccess) : PostModel
    {
        $this->adminAccess = $adminAccess;
        
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
            'uid'         => $this->uid,
            'name'        => $this->name,
            'shortName'   => $this->shortName,
            'adminAccess' => $this->adminAccess,
            'allowAccess' => $this->allowAccess,
        ];
    }
}
