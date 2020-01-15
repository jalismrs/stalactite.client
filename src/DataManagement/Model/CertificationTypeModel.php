<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * CertificationTypeModel
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 */
class CertificationTypeModel extends
    ModelAbstract
{
    /** @var null|string $name */
    private $name;
    
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
     * @return CertificationTypeModel
     */
    public function setName(?string $name) : CertificationTypeModel
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function asArray() : array
    {
        return [
            'uid'  => $this->uid,
            'name' => $this->name
        ];
    }
}
