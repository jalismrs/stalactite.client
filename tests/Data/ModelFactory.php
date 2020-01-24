<?php
declare(strict_types=1);

namespace Test\Data;

use Jalismrs\Stalactite\Client\Data\Model\CertificationGraduationModel;
use Jalismrs\Stalactite\Client\Data\Model\CertificationTypeModel;
use Jalismrs\Stalactite\Client\Data\Model\CustomerModel;
use Jalismrs\Stalactite\Client\Data\Model\DomainModel;
use Jalismrs\Stalactite\Client\Data\Model\PhoneLineModel;
use Jalismrs\Stalactite\Client\Data\Model\PhoneTypeModel;
use Jalismrs\Stalactite\Client\Data\Model\PostModel;
use Jalismrs\Stalactite\Client\Data\Model\UserModel;

/**
 * ModelFactory
 *
 * @package Test\Data
 */
abstract class ModelFactory
{
    /**
     * @return UserModel
     */
    public static function getTestableUser(): UserModel
    {
        $model = new UserModel();
        $model
            ->setFirstName('azerty')
            ->setLastName('uiop')
            ->setGender('male')
            ->setEmail('goodMorning@hello.hi')
            ->setGoogleId('0123456789')
            ->setAdmin(false)
            ->setBirthday('2000-01-01')
            ->addPost(self::getTestablePost())
            ->addLead(self::getTestablePost())
            ->addPhoneLine(self::getTestablePhoneLine())
            ->addCertification(self::getTestableCertificationGraduation())
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * @return PostModel
     */
    public static function getTestablePost(): PostModel
    {
        $model = new PostModel();
        $model
            ->setName('azerty')
            ->setAdminAccess(false)
            ->setAccess(false)
            ->setShortName('aze')
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * @return DomainModel
     */
    public static function getTestableDomain(): DomainModel
    {
        $model = new DomainModel();
        $model
            ->setName('azerty')
            ->setType('api')
            ->setApiKey('azertyuiopqsdfghjklmwxcvbn')
            ->setExternalAuth(false)
            ->setGenerationDate('2000-01-01')
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * @return CustomerModel
     */
    public static function getTestableCustomer(): CustomerModel
    {
        $model = new CustomerModel();
        $model
            ->setEmail('goodmorning@hello.hi')
            ->setFirstName('azerty')
            ->setLastName('uiop')
            ->setGoogleId('0123456789')
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * @return CertificationGraduationModel
     */
    public static function getTestableCertificationGraduation(): CertificationGraduationModel
    {
        $model = new CertificationGraduationModel();
        $model
            ->setDate('2000-01-01')
            ->setType(self::getTestableCertificationType())
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * @return PhoneLineModel
     */
    public static function getTestablePhoneLine(): PhoneLineModel
    {
        $model = new PhoneLineModel();
        $model
            ->setValue('0123456789')
            ->setType(self::getTestablePhoneType())
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * @return PhoneTypeModel
     */
    public static function getTestablePhoneType(): PhoneTypeModel
    {
        $model = new PhoneTypeModel();
        $model
            ->setName('azerty')
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * @return CertificationTypeModel
     */
    public static function getTestableCertificationType(): CertificationTypeModel
    {
        $model = new CertificationTypeModel();
        $model
            ->setName('azerty')
            ->setUid('azertyuiop');

        return $model;
    }
}
