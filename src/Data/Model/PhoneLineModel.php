<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * PhoneLineModel
 *
 * @package Jalismrs\Stalactite\Client\Data\Model
 */
class PhoneLineModel extends
    ModelAbstract
{
    /**
     * @var null|\Jalismrs\Stalactite\Client\Data\Model\PhoneTypeModel
     */
    private $type;
    /**
     * @var null|string
     */
    private $value;
    
    /**
     * getType
     *
     * @return null|\Jalismrs\Stalactite\Client\Data\Model\PhoneTypeModel
     */
    public function getType() : ?PhoneTypeModel
    {
        return $this->type;
    }
    
    /**
     * setType
     *
     * @param null|\Jalismrs\Stalactite\Client\Data\Model\PhoneTypeModel $type
     *
     * @return $this
     */
    public function setType(?PhoneTypeModel $type) : self
    {
        $this->type = $type;
        
        return $this;
    }
    
    /**
     * getValue
     *
     * @return null|string
     */
    public function getValue() : ?string
    {
        return $this->value;
    }
    
    /**
     * setValue
     *
     * @param null|string $value
     *
     * @return $this
     */
    public function setValue(?string $value) : self
    {
        $this->value = $value;
        
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
            'uid'   => $this->uid,
            'value' => $this->value,
            'type'  => null === $this->type
                ? null
                : $this->type->asArray(),
        ];
    }
}
