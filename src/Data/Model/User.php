<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * User
 *
 * @package Jalismrs\Stalactite\Service\Data\Model
 */
class User extends
    AbstractModel
{
    /**
     * @var null|string
     */
    private $email;

    /**
     * @var null|string
     */
    private $googleId;

    /**
     * @var null|string
     */
    private $lastName;

    /**
     * @var null|string
     */
    private $firstName;

    /**
     * @var bool
     */
    private $admin = false;

    /**
     * @var array
     */
    private $posts = [];

    /**
     * @var array
     */
    private $leads = [];

    /**
     * getEmail
     *
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * setEmail
     *
     * @param null|string $email
     *
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * getGoogleId
     *
     * @return null|string
     */
    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    /**
     * setGoogleId
     *
     * @param null|string $googleId
     *
     * @return $this
     */
    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * getLastName
     *
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * setLastName
     *
     * @param null|string $lastName
     *
     * @return $this
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * getFirstName
     *
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * setFirstName
     *
     * @param null|string $firstName
     *
     * @return $this
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * isAdmin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * setAdmin
     *
     * @param bool $admin
     *
     * @return $this
     */
    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * hasAdminPost
     *
     * @return bool
     */
    public function hasAdminPost(): bool
    {
        /** @var Post $post */
        foreach ($this->posts as $post) {
            if ($post->hasAdminAccess()) {
                return true;
            }
        }

        return false;
    }

    /**
     * getPosts
     *
     * @return array
     */
    public function getPosts(): array
    {
        return $this->posts;
    }

    /**
     * setPosts
     *
     * @param array $posts
     *
     * @return $this
     */
    public function setPosts(array $posts): self
    {
        $this->posts = [];
        foreach ($posts as $post) {
            $this->addPost($post);
        }

        return $this;
    }

    /**
     * addPost
     *
     * @param Post $postModel
     *
     * @return $this
     */
    public function addPost(Post $postModel): self
    {
        $this->posts[] = $postModel;

        return $this;
    }

    /**
     * getLeads
     *
     * @return array
     */
    public function getLeads(): array
    {
        return $this->leads;
    }

    /**
     * setLeads
     *
     * @param array $leads
     *
     * @return $this
     */
    public function setLeads(array $leads): self
    {
        $this->leads = [];
        foreach ($leads as $lead) {
            $this->addLead($lead);
        }

        return $this;
    }

    /**
     * addLead
     *
     * @param Post $leadModel
     *
     * @return $this
     */
    public function addLead(Post $leadModel): self
    {
        $this->leads[] = $leadModel;

        return $this;
    }
}
