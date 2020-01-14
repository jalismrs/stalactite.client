<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\DataManagement\Model;

use jalismrs\Stalactite\Client\AbstractModel;

class CertificationGraduation extends AbstractModel
{
    /** @var null|CertificationType $type */
    private $type;

    /** @var null|string $date */
    private $date;

    /**
     * @return CertificationType|null
     */
    public function getType(): ?CertificationType
    {
        return $this->type;
    }

    /**
     * @param CertificationType|null $type
     * @return CertificationGraduation
     */
    public function setType(?CertificationType $type): CertificationGraduation
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * @param string|null $date
     * @return CertificationGraduation
     */
    public function setDate(?string $date): CertificationGraduation
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'uid' => $this->uid,
            'type' => $this->type ? $this->type->asArray() : null,
            'date' => $this->date
        ];
    }
}
