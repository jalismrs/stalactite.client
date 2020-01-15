<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * UserModel
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 */
class UserModel extends
    ModelAbstract
{
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
    
    /** @var boolean $admin */
    private $admin = false;
    
    /** @var null|string $location */
    private $location;
    
    /** @var null|string $office */
    private $office;
    
    /** @var array|PostModel[] $posts */
    private $posts = [];
    
    /** @var array|PostModel[] $leads */
    private $leads = [];
    
    /** @var array|PhoneLineModel[] $phoneLines */
    private $phoneLines = [];
    
    /** @var array|CertificationTypeModel[] $certifications */
    private $certifications = [];
    
    /**
     * @return string
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }
    
    /**
     * @param string $email
     *
     * @return UserModel
     */
    public function setEmail(?string $email) : UserModel
    {
        $this->email = $email;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getGoogleId() : ?string
    {
        return $this->googleId;
    }
    
    /**
     * @param string $googleId
     *
     * @return UserModel
     */
    public function setGoogleId(?string $googleId) : UserModel
    {
        $this->googleId = $googleId;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getBirthday() : ?string
    {
        return $this->birthday;
    }
    
    /**
     * @param string $birthday
     *
     * @return UserModel
     */
    public function setBirthday(?string $birthday) : UserModel
    {
        $this->birthday = $birthday;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLastName() : ?string
    {
        return $this->lastName;
    }
    
    /**
     * @param string $lastName
     *
     * @return UserModel
     */
    public function setLastName(?string $lastName) : UserModel
    {
        $this->lastName = $lastName;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getFirstName() : ?string
    {
        return $this->firstName;
    }
    
    /**
     * @param string $firstName
     *
     * @return UserModel
     */
    public function setFirstName(?string $firstName) : UserModel
    {
        $this->firstName = $firstName;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getGender() : ?string
    {
        return $this->gender;
    }
    
    /**
     * @param string $gender
     *
     * @return UserModel
     */
    public function setGender(?string $gender) : UserModel
    {
        $this->gender = $gender;
        
        return $this;
    }
    
    /**
     * @return bool|null
     */
    public function isAdmin() : bool
    {
        return $this->admin;
    }
    
    /**
     * @param bool|null $admin
     *
     * @return UserModel
     */
    public function setAdmin(bool $admin) : UserModel
    {
        $this->admin = $admin;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLocation() : ?string
    {
        return $this->location;
    }
    
    /**
     * @param string $location
     *
     * @return UserModel
     */
    public function setLocation(?string $location) : UserModel
    {
        $this->location = $location;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getOffice() : ?string
    {
        return $this->office;
    }
    
    /**
     * @param string $office
     *
     * @return UserModel
     */
    public function setOffice(?string $office) : UserModel
    {
        $this->office = $office;
        
        return $this;
    }
    
    /**
     * @return array|PostModel[]
     */
    public function getPosts() : array
    {
        return $this->posts;
    }
    
    /**
     * @param array|PostModel[] $posts
     *
     * @return UserModel
     */
    public function setPosts(array $posts) : UserModel
    {
        $this->posts = [];
        foreach ($posts as $post) {
            $this->addPost($post);
        }
        
        return $this;
    }
    
    /**
     * @param PostModel $post
     *
     * @return UserModel
     */
    public function addPost(PostModel $post) : UserModel
    {
        $this->posts[] = $post;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasAdminPost() : bool
    {
        foreach ($this->getPosts() as $post) {
            if ($post->hasAdminPrivilege()) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * @return array|PostModel[]
     */
    public function getLeads() : array
    {
        return $this->leads;
    }
    
    /**
     * @param array|PostModel[] $leads
     *
     * @return UserModel
     */
    public function setLeads(array $leads) : UserModel
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
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\PostModel $lead
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel
     */
    public function addLead(PostModel $lead) : UserModel
    {
        $this->leads[] = $lead;
        
        return $this;
    }
    
    /**
     * @return array|PhoneLineModel[]
     */
    public function getPhoneLines() : array
    {
        return $this->phoneLines;
    }
    
    /**
     * @param array|PhoneLineModel[] $phoneLines
     *
     * @return UserModel
     */
    public function setPhoneLines(array $phoneLines) : UserModel
    {
        $this->phoneLines = [];
        foreach ($phoneLines as $phoneLine) {
            $this->addPhoneLine($phoneLine);
        }
        
        return $this;
    }
    
    /**
     * @param PhoneLineModel $phoneLine
     *
     * @return UserModel
     */
    public function addPhoneLine(PhoneLineModel $phoneLine) : UserModel
    {
        $this->phoneLines[] = $phoneLine;
        
        return $this;
    }
    
    /**
     * @return array|CertificationTypeModel[]
     */
    public function getCertifications() : array
    {
        return $this->certifications;
    }
    
    /**
     * @param array|CertificationTypeModel[] $certifications
     *
     * @return UserModel
     */
    public function setCertifications(array $certifications) : UserModel
    {
        $this->certifications = [];
        foreach ($certifications as $certification) {
            $this->addCertification($certification);
        }
        
        return $this;
    }
    
    /**
     * @param CertificationGraduationModel $certification
     *
     * @return UserModel
     */
    public function addCertification(CertificationGraduationModel $certification) : UserModel
    {
        $this->certifications[] = $certification;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function asMinimalArray() : array
    {
        return [
            'uid'       => $this->uid,
            'firstName' => $this->firstName,
            'lastName'  => $this->lastName,
            'email'     => $this->email,
            'gender'    => $this->gender,
            'googleId'  => $this->googleId,
            'location'  => $this->location,
            'office'    => $this->office,
            'admin'     => $this->admin,
            'birthday'  => $this->birthday
        ];
    }
    
    /**
     * @return array
     */
    public function asArray() : array
    {
        $user                   = $this->asMinimalArray();
        $user['posts']          = $this->getPostsAsArray();
        $user['leads']          = $this->getLeadsAsArray();
        $user['phoneLines']     = $this->getPhoneLinesAsArray();
        $user['certifications'] = $this->getCertificationsAsArray();
        
        return $user;
    }
    
    /**
     * @return array
     */
    public function getPostsAsArray() : array
    {
        $posts = [];
        
        /** @var PostModel $post */
        foreach ($this->posts as $post) {
            $posts[] = $post->asArray();
        }
        
        return $posts;
    }
    
    /**
     * @return array
     */
    public function getLeadsAsArray() : array
    {
        $leads = [];
        
        /** @var PostModel $lead */
        foreach ($this->leads as $lead) {
            $leads[] = $lead->asArray();
        }
        
        return $leads;
    }
    
    /**
     * @return array
     */
    public function getPhoneLinesAsArray() : array
    {
        $phoneLines = [];
        
        /** @var PhoneLineModel $phoneLine */
        foreach ($this->phoneLines as $phoneLine) {
            $phoneLines[] = $phoneLine->asArray();
        }
        
        return $phoneLines;
    }
    
    /**
     * getCertificationsAsArray
     *
     * @return array
     */
    public function getCertificationsAsArray() : array
    {
        $certifications = [];
        
        /** @var CertificationGraduationModel $certification */
        foreach ($this->certifications as $certification) {
            $certifications[] = $certification->asArray();
        }
        
        return $certifications;
    }
}
