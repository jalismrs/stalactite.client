<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;
use function array_map;
use function array_reduce;

/**
 * UserModel
 *
 * @package Jalismrs\Stalactite\Client\Data\Model
 */
class UserModel extends
    ModelAbstract
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
    private $birthday;
    /**
     * @var null|string
     */
    private $lastName;
    /**
     * @var null|string
     */
    private $firstName;
    /**
     * @var null|string
     */
    private $gender;
    /**
     * @var bool
     */
    private $admin = false;
    /**
     * @var null|string
     */
    private $location;
    /**
     * @var null|string
     */
    private $office;
    /**
     * @var array
     */
    private $posts = [];
    /**
     * @var array
     */
    private $leads = [];
    /**
     * @var array
     */
    private $phoneLines = [];
    /**
     * @var array
     */
    private $certifications = [];
    
    /**
     * getEmail
     *
     * @return null|string
     */
    public function getEmail() : ?string
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
    public function setEmail(?string $email) : self
    {
        $this->email = $email;
        
        return $this;
    }
    
    /**
     * getGoogleId
     *
     * @return null|string
     */
    public function getGoogleId() : ?string
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
    public function setGoogleId(?string $googleId) : self
    {
        $this->googleId = $googleId;
        
        return $this;
    }
    
    /**
     * getBirthday
     *
     * @return null|string
     */
    public function getBirthday() : ?string
    {
        return $this->birthday;
    }
    
    /**
     * setBirthday
     *
     * @param null|string $birthday
     *
     * @return $this
     */
    public function setBirthday(?string $birthday) : self
    {
        $this->birthday = $birthday;
        
        return $this;
    }
    
    /**
     * getLastName
     *
     * @return null|string
     */
    public function getLastName() : ?string
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
    public function setLastName(?string $lastName) : self
    {
        $this->lastName = $lastName;
        
        return $this;
    }
    
    /**
     * getFirstName
     *
     * @return null|string
     */
    public function getFirstName() : ?string
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
    public function setFirstName(?string $firstName) : self
    {
        $this->firstName = $firstName;
        
        return $this;
    }
    
    /**
     * getGender
     *
     * @return null|string
     */
    public function getGender() : ?string
    {
        return $this->gender;
    }
    
    /**
     * setGender
     *
     * @param null|string $gender
     *
     * @return $this
     */
    public function setGender(?string $gender) : self
    {
        $this->gender = $gender;
        
        return $this;
    }
    
    /**
     * isAdmin
     *
     * @return bool
     */
    public function isAdmin() : bool
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
    public function setAdmin(bool $admin) : self
    {
        $this->admin = $admin;
        
        return $this;
    }
    
    /**
     * getLocation
     *
     * @return null|string
     */
    public function getLocation() : ?string
    {
        return $this->location;
    }
    
    /**
     * setLocation
     *
     * @param null|string $location
     *
     * @return $this
     */
    public function setLocation(?string $location) : self
    {
        $this->location = $location;
        
        return $this;
    }
    
    /**
     * getOffice
     *
     * @return null|string
     */
    public function getOffice() : ?string
    {
        return $this->office;
    }
    
    /**
     * setOffice
     *
     * @param null|string $office
     *
     * @return $this
     */
    public function setOffice(?string $office) : self
    {
        $this->office = $office;
        
        return $this;
    }
    
    /**
     * getPosts
     *
     * @return array
     */
    public function getPosts() : array
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
    public function setPosts(array $posts) : self
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
     * @param \Jalismrs\Stalactite\Client\Data\Model\PostModel $postModel
     *
     * @return $this
     */
    public function addPost(PostModel $postModel) : self
    {
        $this->posts[] = $postModel;
        
        return $this;
    }
    
    /**
     * hasAdminPost
     *
     * @return bool
     */
    public function hasAdminPost() : bool
    {
        return array_reduce(
            $this->getPosts(),
            static function(bool $carry, PostModel $postModel) : bool {
                return $carry
                    ||
                    $postModel->hasAdminAccess();
            },
            false,
        );
    }
    
    /**
     * getLeads
     *
     * @return array
     */
    public function getLeads() : array
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
    public function setLeads(array $leads) : self
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
     * @param \Jalismrs\Stalactite\Client\Data\Model\PostModel $leadModel
     *
     * @return $this
     */
    public function addLead(PostModel $leadModel) : self
    {
        $this->leads[] = $leadModel;
        
        return $this;
    }
    
    /**
     * getPhoneLines
     *
     * @return array
     */
    public function getPhoneLines() : array
    {
        return $this->phoneLines;
    }
    
    /**
     * setPhoneLines
     *
     * @param array $phoneLines
     *
     * @return $this
     */
    public function setPhoneLines(array $phoneLines) : self
    {
        $this->phoneLines = [];
        foreach ($phoneLines as $phoneLine) {
            $this->addPhoneLine($phoneLine);
        }
        
        return $this;
    }
    
    /**
     * addPhoneLine
     *
     * @param \Jalismrs\Stalactite\Client\Data\Model\PhoneLineModel $phoneLineModel
     *
     * @return $this
     */
    public function addPhoneLine(PhoneLineModel $phoneLineModel) : self
    {
        $this->phoneLines[] = $phoneLineModel;
        
        return $this;
    }
    
    /**
     * getCertifications
     *
     * @return array
     */
    public function getCertifications() : array
    {
        return $this->certifications;
    }
    
    /**
     * setCertifications
     *
     * @param array $certifications
     *
     * @return $this
     */
    public function setCertifications(array $certifications) : self
    {
        $this->certifications = [];
        foreach ($certifications as $certification) {
            $this->addCertification($certification);
        }
        
        return $this;
    }
    
    /**
     * addCertification
     *
     * @param \Jalismrs\Stalactite\Client\Data\Model\CertificationGraduationModel $certificationGraduationModel
     *
     * @return $this
     */
    public function addCertification(CertificationGraduationModel $certificationGraduationModel) : self
    {
        $this->certifications[] = $certificationGraduationModel;
        
        return $this;
    }
    
    /**
     * asArray
     *
     * @return array
     */
    public function asArray() : array
    {
        return array_merge(
            $this->asMinimalArray(),
            [
                'certifications' => array_map(
                    static function(CertificationGraduationModel $certificationGraduationModel) : array {
                        return $certificationGraduationModel->asArray();
                    },
                    $this->certifications
                ),
                'leads'          => array_map(
                    static function(PostModel $leadModel) : array {
                        return $leadModel->asArray();
                    },
                    $this->leads
                ),
                'phoneLines'     => array_map(
                    static function(PhoneLineModel $phoneLineModel) : array {
                        return $phoneLineModel->asArray();
                    },
                    $this->phoneLines
                ),
                'posts'          => array_map(
                    static function(PostModel $postModel) : array {
                        return $postModel->asArray();
                    },
                    $this->posts
                ),
            ],
        );
    }
    
    /**
     * asMinimalArray
     *
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
            'birthday'  => $this->birthday,
        ];
    }
}
