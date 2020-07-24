<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

class DomainUserRelation extends AbstractModel
{
    private ?Domain $domain = null;
    private ?User $user = null;

    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    public function setDomain(?Domain $domainModel): self
    {
        $this->domain = $domainModel;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $userModel): self
    {
        $this->user = $userModel;

        return $this;
    }

    public static function getSchema(): array
    {
        return [
            'uid' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'domain' => [
                'type' => JsonRule::OBJECT_TYPE,
                'schema' => Domain::getSchema()
            ],
            'user' => [
                'type' => JsonRule::OBJECT_TYPE,
                'schema' => User::getSchema()
            ]
        ];
    }
}
