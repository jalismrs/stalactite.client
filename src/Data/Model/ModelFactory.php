<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

/**
 * Class ModelFactory
 *
 * @package Jalismrs\Stalactite\Client\Data\Model
 * Factory to instantiate models from arrays
 */
abstract class ModelFactory
{
    /**
     * createUserModel
     *
     * @static
     *
     * @param array $data
     *
     * @return User
     */
    public static function createUserModel(array $data): User
    {
        $model = new User();
        $model
            ->setAdmin($data['admin'] ?? false)
            ->setBirthday($data['birthday'] ?? null)
            ->setEmail($data['email'] ?? null)
            ->setFirstName($data['firstName'] ?? null)
            ->setGender($data['gender'] ?? null)
            ->setGoogleId($data['googleId'] ?? null)
            ->setLastName($data['lastName'] ?? null)
            ->setLocation($data['location'] ?? null)
            ->setOffice($data['office'] ?? null)
            ->setUid($data['uid'] ?? null);

        if (isset($data['phoneLines'])) {
            foreach ($data['phoneLines'] as $phoneLine) {
                $model->addPhoneLine(self::createPhoneLineModel($phoneLine));
            }
        }

        if (isset($data['certifications'])) {
            foreach ($data['certifications'] as $certification) {
                $model->addCertification(self::createCertificationGraduationModel($certification));
            }
        }

        if (isset($data['posts'])) {
            foreach ($data['posts'] as $post) {
                $model->addPost(self::createPostModel($post));
            }
        }

        if (isset($data['leads'])) {
            foreach ($data['leads'] as $lead) {
                $model->addLead(self::createPostModel($lead));
            }
        }

        return $model;
    }

    /**
     * createDomainModel
     *
     * @static
     *
     * @param array $data
     *
     * @return Domain
     */
    public static function createDomainModel(array $data): Domain
    {
        $model = new Domain();
        $model
            ->setApiKey($data['apiKey'] ?? null)
            ->setExternalAuth($data['externalAuth'] ?? false)
            ->setGenerationDate($data['generationDate'] ?? null)
            ->setName($data['name'] ?? null)
            ->setType($data['type'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    /**
     * createCustomerModel
     *
     * @static
     *
     * @param array $data
     *
     * @return Customer
     */
    public static function createCustomerModel(array $data): Customer
    {
        $model = new Customer();
        $model
            ->setEmail($data['email'] ?? null)
            ->setFirstName($data['firstName'] ?? null)
            ->setGoogleId($data['googleId'] ?? null)
            ->setLastName($data['lastName'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    /**
     * createPostModel
     *
     * @static
     *
     * @param array $data
     *
     * @return Post
     */
    public static function createPostModel(array $data): Post
    {
        $model = new Post();
        $model
            ->setAccess($data['allowAccess'] ?? false)
            ->setAdminAccess($data['adminAccess'] ?? false)
            ->setName($data['name'] ?? null)
            ->setShortName($data['shortName'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    /**
     * createCertificationTypeModel
     *
     * @static
     *
     * @param array $data
     *
     * @return CertificationType
     */
    public static function createCertificationTypeModel(array $data): CertificationType
    {
        $model = new CertificationType();
        $model
            ->setName($data['name'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    /**
     * createCertificationGraduationModel
     *
     * @static
     *
     * @param array $data
     *
     * @return CertificationGraduation
     */
    public static function createCertificationGraduationModel(array $data): CertificationGraduation
    {
        $model = new CertificationGraduation();
        $model
            ->setDate($data['date'] ?? null)
            ->setType(
                null === $data['type']
                    ? null
                    : self::createCertificationTypeModel($data['type'])
            )
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    /**
     * createPhoneTypeModel
     *
     * @static
     *
     * @param array $data
     *
     * @return PhoneType
     */
    public static function createPhoneTypeModel(array $data): PhoneType
    {
        $model = new PhoneType();
        $model
            ->setName($data['name'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    /**
     * createPhoneLineModel
     *
     * @static
     *
     * @param array $data
     *
     * @return PhoneLine
     */
    public static function createPhoneLineModel(array $data): PhoneLine
    {
        $model = new PhoneLine();
        $model
            ->setType(
                null === $data['type']
                    ? null
                    : self::createPhoneTypeModel($data['type'])
            )
            ->setValue($data['value'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }
}
