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
    /**
     * @var null|string
     */
    private $name;
    /**
     * @var null|string
     */
    private $shortName;
    /**
     * @var bool
     */
    private $allowAccess = false;
    /**
     * @var bool
     */
    private $adminAccess = false;
    
    /**
     * getName
     *
     * @return null|string
     */
    public function getName() : ?string
    {
        return $this->name;
    }
    
    /**
     * setName
     *
     * @param null|string $name
     *
     * @return $this
     */
    public function setName(?string $name) : self
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * getShortName
     *
     * @return null|string
     */
    public function getShortName() : ?string
    {
        return $this->shortName;
    }
    
    /**
     * setShortName
     *
     * @param null|string $shortName
     *
     * @return $this
     */
    public function setShortName(?string $shortName) : self
    {
        $this->shortName = $shortName;
        
        return $this;
    }
    
    /**
     * allowAccess
     *
     * @return bool
     */
    public function allowAccess() : bool
    {
        return $this->allowAccess;
    }
    
    /**
     * setAccess
     *
     * @param bool $allowAccess
     *
     * @return $this
     */
    public function setAccess(bool $allowAccess) : self
    {
        $this->allowAccess = $allowAccess;
        
        return $this;
    }
    
    /**
     * hasAdminAccess
     *
     * @return bool
     */
    public function hasAdminAccess() : bool
    {
        return $this->adminAccess;
    }
    
    /**
     * setAdminAccess
     *
     * @param bool $adminAccess
     *
     * @return $this
     */
    public function setAdminAccess(bool $adminAccess) : self
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
