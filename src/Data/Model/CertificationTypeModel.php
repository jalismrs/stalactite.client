<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * CertificationTypeModel
 *
 * @package Jalismrs\Stalactite\Client\Data\Model
 */
class CertificationTypeModel extends
    ModelAbstract
{
    /**
     * @var null|string
     */
    private $name;
    
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
     * asArray
     *
     * @return array
     */
    public function asArray() : array
    {
        return [
            'uid'  => $this->uid,
            'name' => $this->name,
        ];
    }
}
