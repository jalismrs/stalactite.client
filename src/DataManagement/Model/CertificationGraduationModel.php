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
    /**
     * @var null|\Jalismrs\Stalactite\Client\DataManagement\Model\CertificationTypeModel
     */
    private $type;
    /**
     * @var
     */
    private $date;
    
    /**
     * getType
     *
     * @return null|\Jalismrs\Stalactite\Client\DataManagement\Model\CertificationTypeModel
     */
    public function getType() : ?CertificationTypeModel
    {
        return $this->type;
    }
    
    /**
     * setType
     *
     * @param null|\Jalismrs\Stalactite\Client\DataManagement\Model\CertificationTypeModel $certificationTypeModel
     *
     * @return $this
     */
    public function setType(?CertificationTypeModel $certificationTypeModel) : self
    {
        $this->type = $certificationTypeModel;
        
        return $this;
    }
    
    /**
     * getDate
     *
     * @return null|string
     */
    public function getDate() : ?string
    {
        return $this->date;
    }
    
    /**
     * setDate
     *
     * @param null|string $date
     *
     * @return $this
     */
    public function setDate(?string $date) : self
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
