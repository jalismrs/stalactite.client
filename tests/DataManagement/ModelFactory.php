<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\DataManagement;

use Jalismrs\Stalactite\Client\DataManagement\Model\CertificationGraduation;
use Jalismrs\Stalactite\Client\DataManagement\Model\CertificationType;
use Jalismrs\Stalactite\Client\DataManagement\Model\Customer;
use Jalismrs\Stalactite\Client\DataManagement\Model\Domain;
use Jalismrs\Stalactite\Client\DataManagement\Model\PhoneLine;
use Jalismrs\Stalactite\Client\DataManagement\Model\PhoneType;
use Jalismrs\Stalactite\Client\DataManagement\Model\Post;
use Jalismrs\Stalactite\Client\DataManagement\Model\User;

/**
 * ModelFactory
 *
 * @package Jalismrs\Stalactite\Test\DataManagement
 */
abstract class ModelFactory
{
    /**
     * @return User
     */
    public static function getTestableUser() : User
    {
        $model = new User();
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
     * @return Post
     */
    public static function getTestablePost() : Post
    {
        $model = new Post();
        $model
            ->setName('azerty')
            ->setAdminAccess(false)
            ->setAccess(false)
            ->setShortName('aze')
            ->setUid('azertyuiop');
        
        return $model;
    }
    
    /**
     * @return Domain
     */
    public static function getTestableDomain() : Domain
    {
        $model = new Domain();
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
     * @return Customer
     */
    public static function getTestableCustomer() : Customer
    {
        $model = new Customer();
        $model
            ->setEmail('goodmorning@hello.hi')
            ->setFirstName('azerty')
            ->setLastName('uiop')
            ->setGoogleId('0123456789')
            ->setUid('azertyuiop');
        
        return $model;
    }
    
    /**
     * @return CertificationGraduation
     */
    public static function getTestableCertificationGraduation() : CertificationGraduation
    {
        $model = new CertificationGraduation();
        $model
            ->setDate('2000-01-01')
            ->setType(self::getTestableCertificationType())
            ->setUid('azertyuiop');
        
        return $model;
    }
    
    /**
     * @return PhoneLine
     */
    public static function getTestablePhoneLine() : PhoneLine
    {
        $model = new PhoneLine();
        $model
            ->setValue('0123456789')
            ->setType(self::getTestablePhoneType())
            ->setUid('azertyuiop');
        
        return $model;
    }
    
    /**
     * @return PhoneType
     */
    public static function getTestablePhoneType() : PhoneType
    {
        $model = new PhoneType();
        $model
            ->setName('azerty')
            ->setUid('azertyuiop');
        
        return $model;
    }
    
    /**
     * @return CertificationType
     */
    public static function getTestableCertificationType() : CertificationType
    {
        $model = new CertificationType();
        $model
            ->setName('azerty')
            ->setUid('azertyuiop');
        
        return $model;
    }
}
