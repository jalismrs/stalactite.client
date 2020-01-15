<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * CertificationType
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 */
class CertificationType extends
    AbstractModel
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
     * @return CertificationType
     */
    public function setName(?string $name) : CertificationType
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
