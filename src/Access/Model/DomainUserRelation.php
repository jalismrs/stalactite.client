<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Util\Serializer;

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
     *
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public function asArray(): array
    {
        $serializer = Serializer::create();
        
        return [
            'uid' => $this->uid,
            'domain' => $serializer->normalize(
                $this->domain,
                [
                    'groups' => [
                        'main',
                    ],
                ]
            ),
            'user' => null === $this->user
                ? null
                : $this->user->asArray(),
        ];
    }
}
