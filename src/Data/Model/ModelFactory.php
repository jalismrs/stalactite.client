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
     * @return UserModel
     */
    public static function createUserModel(array $data): UserModel
    {
        $model = new UserModel();
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
     * @return DomainModel
     */
    public static function createDomainModel(array $data): DomainModel
    {
        $model = new DomainModel();
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
     * @return CustomerModel
     */
    public static function createCustomerModel(array $data): CustomerModel
    {
        $model = new CustomerModel();
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
     * @return PostModel
     */
    public static function createPostModel(array $data): PostModel
    {
        $model = new PostModel();
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
     * @return CertificationTypeModel
     */
    public static function createCertificationTypeModel(array $data): CertificationTypeModel
    {
        $model = new CertificationTypeModel();
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
     * @return CertificationGraduationModel
     */
    public static function createCertificationGraduationModel(array $data): CertificationGraduationModel
    {
        $model = new CertificationGraduationModel();
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
     * @return PhoneTypeModel
     */
    public static function createPhoneTypeModel(array $data): PhoneTypeModel
    {
        $model = new PhoneTypeModel();
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
     * @return PhoneLineModel
     */
    public static function createPhoneLineModel(array $data): PhoneLineModel
    {
        $model = new PhoneLineModel();
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
