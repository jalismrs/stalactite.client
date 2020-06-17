<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\User;

/**
 * DomainUserRelation
 *
 * @package Jalismrs\Stalactite\Service\Access\Model
 */
class DomainUserRelation extends DomainRelation
{
    private ?User $user = null;

    /**
     * getUser
     *
     * @return null|User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * setUser
     *
     * @param null|User $userModel
     *
     * @return $this
     */
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
