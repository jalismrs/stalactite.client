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
class User extends
    AbstractModel
{
    use ModelNameTrait;
    
    /**
     * email
     *
     * @var string|null
     */
    private ?string $email = null;
    /**
     * googleId
     *
     * @var string|null
     */
    private ?string $googleId = null;
    /**
     * admin
     *
     * @var bool
     */
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
     * getSchema
     *
     * @static
     * @return array[]
     *
     * @codeCoverageIgnore
     */
    public static function getSchema(): array
    {
        return [
            'uid' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'firstName' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'lastName' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'email' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'googleId' => [
                'type' => JsonRule::STRING_TYPE,
                'null' => true,
            ],
            'admin' => [
                'type' => JsonRule::BOOLEAN_TYPE,
            ],
        ];
    }

    /**
     * getEmail
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * setEmail
     *
     * @param string|null $email
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * getGoogleId
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    /**
     * setGoogleId
     *
     * @param string|null $googleId
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * isAdmin
     *
     * @return bool
     *
     * @codeCoverageIgnore
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
     *
     * @codeCoverageIgnore
     */
    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * getPosts
     *
     * @return array|Post[]
     *
     * @codeCoverageIgnore
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
     *
     * @codeCoverageIgnore
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
     *
     * @codeCoverageIgnore
     */
    public function addPost(Post $postModel): self
    {
        $this->posts[] = $postModel;

        return $this;
    }

    /**
     * getLeads
     *
     * @return array|Post[]
     *
     * @codeCoverageIgnore
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
     *
     * @codeCoverageIgnore
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
     *
     * @codeCoverageIgnore
     */
    public function addLead(Post $leadModel): self
    {
        $this->leads[] = $leadModel;

        return $this;
    }

    /**
     * hasPermission
     *
     * @param string $permission
     *
     * @return bool
     */
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

    /**
     * hasExplicitPermission
     *
     * @param string $permission
     *
     * @return bool
     */
    public function hasExplicitPermission(string $permission): bool
    {
        foreach ($this->posts as $post) {
            if ($post->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }
}
