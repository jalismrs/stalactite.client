<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\Test\DataManagement;

use jalismrs\Stalactite\Client\DataManagement\Model\CertificationGraduation;
use jalismrs\Stalactite\Client\DataManagement\Model\CertificationType;
use jalismrs\Stalactite\Client\DataManagement\Model\Customer;
use jalismrs\Stalactite\Client\DataManagement\Model\Domain;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneLine;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneType;
use jalismrs\Stalactite\Client\DataManagement\Model\Post;
use jalismrs\Stalactite\Client\DataManagement\Model\User;

abstract class ModelFactory
{
    /**
     * @return User
     */
    public static function getTestableUser(): User
    {
        $user = new User();
        $user->setFirstName('azerty')
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

        return $user;
    }

    /**
     * @return Post
     */
    public static function getTestablePost(): Post
    {
        $post = new Post();
        $post->setName('azerty')
            ->setAdminAccess(false)
            ->setAccess(false)
            ->setShortName('aze')
            ->setUid('azertyuiop');

        return $post;
    }

    /**
     * @return Domain
     */
    public static function getTestableDomain(): Domain
    {
        $domain = new Domain();
        $domain->setName('azerty')
            ->setType('api')
            ->setApiKey('azertyuiopqsdfghjklmwxcvbn')
            ->setExternalAuth(false)
            ->setGenerationDate('2000-01-01')
            ->setUid('azertyuiop');

        return $domain;
    }

    /**
     * @return Customer
     */
    public static function getTestableCustomer(): Customer
    {
        $customer = new Customer();
        $customer->setEmail('goodmorning@hello.hi')
            ->setFirstName('azerty')
            ->setLastName('uiop')
            ->setGoogleId('0123456789')
            ->setUid('azertyuiop');

        return $customer;
    }

    /**
     * @return CertificationGraduation
     */
    public static function getTestableCertificationGraduation(): CertificationGraduation
    {
        $certificationGraduation = new CertificationGraduation();
        $certificationGraduation->setDate('2000-01-01')->setType(self::getTestableCertificationType())->setUid('azertyuiop');

        return $certificationGraduation;
    }

    /**
     * @return PhoneLine
     */
    public static function getTestablePhoneLine(): PhoneLine
    {
        $phoneLine = new PhoneLine();
        $phoneLine->setValue('0123456789')->setType(self::getTestablePhoneType())->setUid('azertyuiop');

        return $phoneLine;
    }

    /**
     * @return PhoneType
     */
    public static function getTestablePhoneType(): PhoneType
    {
        $type = new PhoneType();
        $type->setName('azerty')->setUid('azertyuiop');

        return $type;
    }

    /**
     * @return CertificationType
     */
    public static function getTestableCertificationType(): CertificationType
    {
        $type = new CertificationType();
        $type->setName('azerty')->setUid('azertyuiop');

        return $type;
    }
}
