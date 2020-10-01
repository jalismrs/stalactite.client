<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

class ServerApp extends AbstractModel
{
    private ?string $name = null;
    private ?string $tokenSignatureKey = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ServerApp
     */
    public function setName(?string $name): ServerApp
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTokenSignatureKey(): ?string
    {
        return $this->tokenSignatureKey;
    }

    /**
     * @param string|null $tokenSignatureKey
     * @return ServerApp
     */
    public function setTokenSignatureKey(?string $tokenSignatureKey): ServerApp
    {
        $this->tokenSignatureKey = $tokenSignatureKey;
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
            'tokenSignatureKey' => [
                'type' => JsonRule::STRING_TYPE
            ]
        ];
    }
}
