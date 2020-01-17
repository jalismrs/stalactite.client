<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * PhoneLineModel
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 */
class PhoneLineModel extends
    ModelAbstract
{
    /** @var null|PhoneTypeModel $type */
    private $type;
    
    /** @var null|string $value */
    private $value;
    
    /**
     * @return PhoneTypeModel|null
     */
    public function getType() : ?PhoneTypeModel
    {
        return $this->type;
    }
    
    /**
     * @param PhoneTypeModel|null $type
     *
     * @return PhoneLineModel
     */
    public function setType(?PhoneTypeModel $type) : PhoneLineModel
    {
        $this->type = $type;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getValue() : ?string
    {
        return $this->value;
    }
    
    /**
     * @param string|null $value
     *
     * @return PhoneLineModel
     */
    public function setValue(?string $value) : PhoneLineModel
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
