<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\Model;

/**
 * Class ModelFactory
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\Model
 * Factory to instantiate models from arrays
 */
abstract class ModelFactory
{
    /**
     * @param array $data
     *
     * @return UserModel
     */
    public static function createUser(array $data) : UserModel
    {
        $model = new UserModel();
        $model
            ->setGoogleId($data['googleId'] ?? null)
            ->setLastName($data['lastName'] ?? null)
            ->setFirstName($data['firstName'] ?? null)
            ->setEmail($data['email'] ?? null)
            ->setAdmin($data['admin'] ?? false)
            ->setBirthday($data['birthday'] ?? null)
            ->setOffice($data['office'] ?? null)
            ->setLocation($data['location'] ?? null)
            ->setGender($data['gender'] ?? null)
            ->setUid($data['uid'] ?? null);
        
        if (isset($data['phoneLines'])) {
            foreach ($data['phoneLines'] as $phoneLine) {
                $model->addPhoneLine(self::createPhoneLine($phoneLine));
            }
        }
        
        if (isset($data['certifications'])) {
            foreach ($data['certifications'] as $certification) {
                $model->addCertification(self::createCertificationGraduation($certification));
            }
        }
        
        if (isset($data['posts'])) {
            foreach ($data['posts'] as $post) {
                $model->addPost(self::createPost($post));
            }
        }
        
        if (isset($data['leads'])) {
            foreach ($data['leads'] as $lead) {
                $model->addLead(self::createPost($lead));
            }
        }
        
        return $model;
    }
    
    /**
     * @param array $data
     *
     * @return DomainModel
     */
    public static function createDomain(array $data) : DomainModel
    {
        $model = new DomainModel();
        $model
            ->setName($data['name'] ?? null)
            ->setGenerationDate($data['generationDate'] ?? null)
            ->setExternalAuth($data['externalAuth'] ?? false)
            ->setApiKey($data['apiKey'] ?? null)
            ->setType($data['type'] ?? null)
            ->setUid($data['uid'] ?? null);
        
        return $model;
    }
    
    /**
     * @param array $data
     *
     * @return CustomerModel
     */
    public static function createCustomer(array $data) : CustomerModel
    {
        $model = new CustomerModel();
        $model
            ->setEmail($data['email'] ?? null)
            ->setFirstName($data['firstName'] ?? null)
            ->setLastName($data['lastName'] ?? null)
            ->setGoogleId($data['googleId'] ?? null)
            ->setUid($data['uid'] ?? null);
        
        return $model;
    }
    
    /**
     * @param array $data
     *
     * @return PostModel
     */
    public static function createPost(array $data) : PostModel
    {
        $model = new PostModel();
        $model
            ->setName($data['name'] ?? null)
            ->setAdminAccess($data['adminAccess'] ?? false)
            ->setAccess($data['allowAccess'] ?? false)
            ->setShortName($data['shortName'] ?? null)
            ->setUid($data['uid'] ?? null);
        
        return $model;
    }
    
    /**
     * @param array $data
     *
     * @return CertificationTypeModel
     */
    public static function createCertificationType(array $data) : CertificationTypeModel
    {
        $model = new CertificationTypeModel();
        $model
            ->setName($data['name'] ?? null)
            ->setUid($data['uid'] ?? null);
        
        return $model;
    }
    
    /**
     * @param array $data
     *
     * @return CertificationGraduationModel
     */
    public static function createCertificationGraduation(array $data) : CertificationGraduationModel
    {
        $model = new CertificationGraduationModel();
        $model
            ->setType(
                $data['type']
                    ? self::createCertificationType($data['type'])
                    : null
            )
            ->setDate($data['date'] ?? null)
            ->setUid($data['uid'] ?? null);
        
        return $model;
    }
    
    /**
     * @param array $data
     *
     * @return PhoneTypeModel
     */
    public static function createPhoneType(array $data) : PhoneTypeModel
    {
        $model = new PhoneTypeModel();
        $model
            ->setName($data['name'] ?? null)
            ->setUid($data['uid'] ?? null);
        
        return $model;
    }
    
    /**
     * @param array $data
     *
     * @return PhoneLineModel
     */
    public static function createPhoneLine(array $data) : PhoneLineModel
    {
        $model = new PhoneLineModel();
        $model
            ->setType(
                $data['type']
                    ? self::createPhoneType($data['type'])
                    : null
            )
            ->setValue($data['value'] ?? null)
            ->setUid($data['uid'] ?? null);
        
        return $model;
    }
}
