<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

class AccessClearance extends AbstractModel
{
    public const NO_ACCESS = null;
    public const ADMIN_ACCESS = 'admin';
    public const USER_ACCESS = 'user';

    private bool $granted;
    private ?string $type;

    public function __construct(bool $granted = false, ?string $type = null)
    {
        $this->granted = $granted;
        $this->type = $type ?? self::NO_ACCESS;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isGranted(): bool
    {
        return $this->granted;
    }

    public function setGranted(bool $granted): self
    {
        $this->granted = $granted;

        return $this;
    }

    public function hasUserAccessGranted(): bool
    {
        return $this->isGranted() && $this->type === self::USER_ACCESS;
    }

    public function hasAdminAccessGranted(): bool
    {
        return $this->isGranted() && $this->type === self::ADMIN_ACCESS;
    }

    public static function getSchema(): array
    {
        return [
            'granted' => [
                'type' => JsonRule::BOOLEAN_TYPE
            ],
            'type' => [
                'type' => JsonRule::STRING_TYPE,
                'null' => true,
                'enum' => [
                    self::USER_ACCESS,
                    self::ADMIN_ACCESS
                ]
            ]
        ];
    }
}
