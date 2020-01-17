<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * PhoneTypeModel
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 */
class PhoneTypeModel extends
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
     * @return PhoneTypeModel
     */
    public function setName(?string $name) : PhoneTypeModel
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
