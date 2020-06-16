<?php

namespace Jalismrs\Stalactite\Client\Tests\Factory\Data;

use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\Permission;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;

abstract class ModelFactory
{
    /**
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
     * @return Post
     */
    public static function getTestablePost(): Post
    {
        $model = new Post();
        $model
            ->setName('azerty')
            ->setShortName('aze')
            ->setUid('azertyuiop')
            ->setPermissions([self::getTestablePermission()]);

        return $model;
    }

    /**
     * @return Permission
     */
    public static function getTestablePermission(): Permission
    {
        $model = new Permission();
        $model
            ->setScope('scope')
            ->setResource('resource')
            ->setOperation('operation')
            ->setUid('azertyuiop');

        return $model;
    }

    /**
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
}