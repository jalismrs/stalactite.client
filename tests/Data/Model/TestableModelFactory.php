<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Data\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Data\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Data\Model\Permission;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;

/**
 * Class ModelFactory
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 */
class TestableModelFactory
{
    /**
     * getTestablePermission
     *
     * @static
     * @return Permission
     */
    public static function getTestablePermission(): Permission
    {
        $model = new Permission(
            'scope',
            'resource',
            'operation'
        );
        $model->setUid('azertyuiop');

        return $model;
    }

    /**
     * getTestableDomainUserRelation
     *
     * @static
     * @return DomainUserRelation
     */
    public static function getTestableDomainUserRelation(): DomainUserRelation
    {
        $model = new DomainUserRelation();
        $model->setUser(self::getTestableUser())
            ->setDomain(self::getTestableDomain())
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * getTestableUser
     *
     * @static
     * @return User
     */
    public static function getTestableUser(): User
    {
        $model = new User();
        $model
            ->setFirstName('azerty')
            ->setLastName('uiop')
            ->setEmail('goodMorning@hello.hi')
            ->setGoogleId('0123456789')
            ->setAdmin(false)
            ->addPost(self::getTestablePost())
            ->addLead(self::getTestablePost())
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * getTestableDomain
     *
     * @static
     * @return Domain
     */
    public static function getTestableDomain(): Domain
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
     * getTestablePost
     *
     * @static
     * @return Post
     */
    public static function getTestablePost(): Post
    {
        $model = new Post();
        $model
            ->setName('azerty')
            ->setShortName('aze')
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * getTestableDomainCustomerRelation
     *
     * @static
     * @return DomainCustomerRelation
     */
    public static function getTestableDomainCustomerRelation(): DomainCustomerRelation
    {
        $model = new DomainCustomerRelation();
        $model->setCustomer(self::getTestableCustomer())
            ->setDomain(self::getTestableDomain())
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * getTestableCustomer
     *
     * @static
     * @return Customer
     */
    public static function getTestableCustomer(): Customer
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
     * getTestableAccessClearance
     *
     * @static
     * @return AccessClearance
     */
    public static function getTestableAccessClearance(): AccessClearance
    {
        $model = new AccessClearance();
        $model->setGranted(false)
            ->setType(AccessClearance::NO_ACCESS);

        return $model;
    }
}
