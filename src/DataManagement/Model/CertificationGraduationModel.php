<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * CertificationGraduationModel
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 */
class CertificationGraduationModel extends
    ModelAbstract
{
    /** @var null|CertificationTypeModel $type */
    private $type;
    
    /** @var null|string $date */
    private $date;
    
    /**
     * @return CertificationTypeModel|null
     */
    public function getType() : ?CertificationTypeModel
    {
        return $this->type;
    }
    
    /**
     * @param CertificationTypeModel|null $type
     *
     * @return CertificationGraduationModel
     */
    public function setType(?CertificationTypeModel $type) : CertificationGraduationModel
    {
        $this->type = $type;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getDate() : ?string
    {
        return $this->date;
    }
    
    /**
     * @param string|null $date
     *
     * @return CertificationGraduationModel
     */
    public function setDate(?string $date) : CertificationGraduationModel
    {
        $this->date = $date;
        
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
            'type' => null === $this->type
                ? null
                : $this->type->asArray(),
            'date' => $this->date,
        ];
    }
}
