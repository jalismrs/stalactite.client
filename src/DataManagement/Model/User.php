<?php

namespace jalismrs\Stalactite\Client\DataManagement\Model;

use jalismrs\Stalactite\Client\AbstractModel;

class User extends AbstractModel
{
    private const USER_PRIVILEGE = 'user';

    private const ADMIN_PRIVILEGE = 'admin';

    private const SUPER_ADMIN_PRIVILEGE = 'superadmin';

    /** @var null|string $email */
    private $email;

    /** @var null|string $googleId */
    private $googleId;

    /** @var null|string $birthday */
    private $birthday;

    /** @var null|string $lastName */
    private $lastName;

    /** @var null|string $firstName */
    private $firstName;

    /** @var null|string $gender */
    private $gender;

    /** @var null|string $privilege */
    private $privilege;

    /** @var null|string $location */
    private $location;

    /** @var null|string $office */
    private $office;

    /** @var array|Post[] $posts */
    private $posts = [];

    /** @var array|Post[] $leads */
    private $leads = [];

    /** @var array|PhoneLine[] $phoneLines */
    private $phoneLines = [];

    /** @var array|CertificationType[] $certifications */
    private $certifications = [];

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    /**
     * @param string $googleId
     * @return User
     */
    public function setGoogleId(?string $googleId): User
    {
        $this->googleId = $googleId;
        return $this;
    }

    /**
     * @return string
     */
    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    /**
     * @param string $birthday
     * @return User
     */
    public function setBirthday(?string $birthday): User
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return User
     */
    public function setGender(?string $gender): User
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrivilege(): ?string
    {
        return $this->privilege;
    }

    public function isAdmin(): bool
    {
        return ($this->privilege === self::ADMIN_PRIVILEGE) || $this->isSuperAdmin() || $this->hasAdminPost();
    }

    public function isSuperAdmin(): bool
    {
        return $this->privilege === self::SUPER_ADMIN_PRIVILEGE;
    }

    /**
     * @param string $privilege
     * @return User
     */
    public function setPrivilege(?string $privilege): User
    {
        $this->privilege = $privilege;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return User
     */
    public function setLocation(?string $location): User
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string
     */
    public function getOffice(): ?string
    {
        return $this->office;
    }

    /**
     * @param string $office
     * @return User
     */
    public function setOffice(?string $office): User
    {
        $this->office = $office;
        return $this;
    }

    /**
     * @return array|Post[]
     */
    public function getPosts(): array
    {
        return $this->posts;
    }

    /**
     * @param array|Post[] $posts
     * @return User
     */
    public function setPosts(array $posts): User
    {
        $this->posts = [];
        foreach ($posts as $post) {
            $this->addPost($post);
        }

        return $this;
    }

    /**
     * @param Post $post
     * @return User
     */
    public function addPost(Post $post): User
    {
        $this->posts[] = $post;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasAdminPost(): bool
    {
        foreach ($this->getPosts() as $post) {
            if ($post->hasAdminPrivilege()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array|Post[]
     */
    public function getLeads(): array
    {
        return $this->leads;
    }

    /**
     * @param array|Post[] $leads
     * @return User
     */
    public function setLeads(array $leads): User
    {
        $this->leads = [];
        foreach ($leads as $lead) {
            $this->addLead($lead);
        }

        return $this;
    }

    public function addLead(Post $lead): User
    {
        $this->leads[] = $lead;
        return $this;
    }

    /**
     * @return array|PhoneLine[]
     */
    public function getPhoneLines(): array
    {
        return $this->phoneLines;
    }

    /**
     * @param array|PhoneLine[] $phoneLines
     * @return User
     */
    public function setPhoneLines(array $phoneLines): User
    {
        $this->phoneLines = [];
        foreach ($phoneLines as $phoneLine) {
            $this->addPhoneLine($phoneLine);
        }

        return $this;
    }

    /**
     * @param PhoneLine $phoneLine
     * @return User
     */
    public function addPhoneLine(PhoneLine $phoneLine): User
    {
        $this->phoneLines[] = $phoneLine;
        return $this;
    }

    /**
     * @return array|CertificationType[]
     */
    public function getCertifications(): array
    {
        return $this->certifications;
    }

    /**
     * @param array|CertificationType[] $certifications
     * @return User
     */
    public function setCertifications(array $certifications): User
    {
        $this->certifications = [];
        foreach ($certifications as $certification) {
            $this->addCertification($certification);
        }

        return $this;
    }

    /**
     * @param CertificationGraduation $certification
     * @return User
     */
    public function addCertification(CertificationGraduation $certification): User
    {
        $this->certifications[] = $certification;
        return $this;
    }

    /**
     * @return array
     */
    public function asMinimalArray(): array
    {
        return [
            'uid' => $this->uid,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'gender' => $this->gender,
            'googleId' => $this->googleId,
            'location' => $this->location,
            'office' => $this->office,
            'privilege' => $this->privilege,
            'birthday' => $this->birthday
        ];
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        $user = $this->asMinimalArray();
        $user['posts'] = $this->getPostsAsArray();
        $user['leads'] = $this->getLeadsAsArray();
        $user['phoneLines'] = $this->getPhoneLinesAsArray();
        $user['certifications'] = $this->getCertificationsAsArray();

        return $user;
    }

    /**
     * @return array
     */
    public function getPostsAsArray(): array
    {
        $posts = [];

        /** @var Post $post */
        foreach ($this->posts as $post) {
            $posts[] = $post->asArray();
        }

        return $posts;
    }

    /**
     * @return array
     */
    public function getLeadsAsArray(): array
    {
        $leads = [];

        /** @var Post $lead */
        foreach ($this->leads as $lead) {
            $leads[] = $lead->asArray();
        }

        return $leads;
    }

    /**
     * @return array
     */
    public function getPhoneLinesAsArray(): array
    {
        $phoneLines = [];

        /** @var PhoneLine $phoneLine */
        foreach ($this->phoneLines as $phoneLine) {
            $phoneLines[] = $phoneLine->asArray();
        }

        return $phoneLines;
    }

    public function getCertificationsAsArray(): array
    {
        $certifications = [];

        /** @var CertificationGraduation $certification */
        foreach ($this->certifications as $certification) {
            $certifications[] = $certification->asArray();
        }

        return $certifications;
    }
}