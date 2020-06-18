<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * User
 *
 * @package Jalismrs\Stalactite\Service\Data\Model
 */
class User extends AbstractModel
{
    private ?string $email = null;
    private ?string $googleId = null;
    private ?string $lastName = null;
    private ?string $firstName = null;
    private bool $admin = false;

    /**
     * @var Post[]|array
     */
    private array $posts = [];

    /**
     * @var Post[]|array
     */
    private array $leads = [];

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
            if ($post instanceof Post) {
                $this->addPost($post);
            }
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
            if ($lead instanceof Post) {
                $this->addLead($lead);
            }
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

    public function hasPermission(string $permission): bool
    {
        if ($this->admin) {
            return true;
        }

        if ($this->hasExplicitPermission($permission)) {
            return true;
        }

        return false;
    }

    public function hasExplicitPermission(string $permission): bool
    {
        foreach ($this->posts as $post) {
            if ($post->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public static function getSchema(): array
    {
        return [
            'uid' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'firstName' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'lastName' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'email' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'googleId' => [
                'type' => JsonRule::STRING_TYPE,
                'null' => true
            ],
            'admin' => [
                'type' => JsonRule::BOOLEAN_TYPE
            ],
        ];
    }
}
