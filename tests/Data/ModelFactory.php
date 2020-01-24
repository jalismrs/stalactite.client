<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Data;

use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;

/**
 * ModelFactory
 *
 * @packageJalismrs\Stalactite\Client\Tests\Data
 */
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
            ->setAdminAccess(false)
            ->setAccess(false)
            ->setShortName('aze')
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
