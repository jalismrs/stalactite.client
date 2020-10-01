<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

class ClientApp extends AbstractModel
{
    private ?string $name = null;
    private ?string $googleOAuthClientId = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGoogleOAuthClientId(): ?string
    {
        return $this->googleOAuthClientId;
    }

    public function setGoogleOAuthClientId(?string $googleOAuthClientId): self
    {
        $this->googleOAuthClientId = $googleOAuthClientId;

        return $this;
    }

    public static function getSchema(): array
    {
        return [
            'uid' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'name' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'googleOAuthClientId' => [
                'type' => JsonRule::STRING_TYPE
            ]
        ];
    }
}
