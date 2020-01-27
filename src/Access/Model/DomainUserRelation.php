<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\User;

/**
 * DomainUserRelation
 *
 * @package Jalismrs\Stalactite\Client\Access\Model
 */
class DomainUserRelation extends
    DomainRelation
{
    /**
     * @var null|User
     */
    private $user;

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

    /**
     * asArray
     *
     * @return array
     */
    public function asArray(): array
    {
        return [
            'uid' => $this->uid,
            'domain' => null === $this->domain
                ? null
                : $this->domain->asArray(),
            'user' => null === $this->user
                ? null
                : $this->user->asArray(),
        ];
    }
}