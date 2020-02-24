<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\User;

/**
 * DomainUserRelation
 *
 * @package Jalismrs\Stalactite\Service\Access\Model
 */
class DomainUserRelation extends
    DomainRelation
{
    /**
     * @var User|null
     */
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
}
