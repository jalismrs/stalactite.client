<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use Jalismrs\Stalactite\Client\Data\Model\ModelFactory as DataModelFactory;

/**
 * Class ModelFactory
 *
 * @package Jalismrs\Stalactite\Service\Data\Model
 * Factory to instantiate models from arrays
 */
abstract class ModelFactory
{
    public static function createUser(array $data): User
    {
        $model = new User();
        $model->setAdmin($data['admin'] ?? false)
            ->setEmail($data['email'] ?? null)
            ->setFirstName($data['firstName'] ?? null)
            ->setGoogleId($data['googleId'] ?? null)
            ->setLastName($data['lastName'] ?? null)
            ->setUid($data['uid'] ?? null);

        foreach ($data['posts'] ?? [] as $post) {
            $model->addPost(self::createPost($post));
        }

        foreach ($data['leads'] ?? [] as $lead) {
            $model->addLead(self::createPost($lead));
        }

        return $model;
    }

    public static function createPost(array $data): Post
    {
        $model = new Post();
        $model->setName($data['name'] ?? null)
            ->setShortName($data['shortName'] ?? null)
            ->setUid($data['uid'] ?? null);

        foreach ($data['permissions'] ?? [] as $permission) {
            $model->addPermission(self::createPermission($permission));
        }

        return $model;
    }

    public static function createDomain(array $data): Domain
    {
        $model = new Domain();
        $model->setApiKey($data['apiKey'] ?? null)
            ->setExternalAuth($data['externalAuth'] ?? false)
            ->setGenerationDate($data['generationDate'] ?? null)
            ->setName($data['name'] ?? null)
            ->setType($data['type'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    public static function createCustomer(array $data): Customer
    {
        $model = new Customer();
        $model->setEmail($data['email'] ?? null)
            ->setFirstName($data['firstName'] ?? null)
            ->setGoogleId($data['googleId'] ?? null)
            ->setLastName($data['lastName'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    public static function createPermission(array $data): Permission
    {
        $model = new Permission(
            $data['scope'] ?? false,
            $data['resource'] ?? false,
            $data['operation'] ?? null
        );
        $model->setUid($data['uid'] ?? null);

        return $model;
    }

    public static function createDomainUserRelation(array $data): DomainUserRelation
    {
        $model = new DomainUserRelation();
        $model->setUser(isset($data['user']) ? DataModelFactory::createUser($data['user']) : null)
            ->setDomain(isset($data['domain']) ? DataModelFactory::createDomain($data['domain']) : null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    public static function createDomainCustomerRelation(array $data): DomainCustomerRelation
    {
        $model = new DomainCustomerRelation();
        $model->setCustomer(isset($data['customer']) ? DataModelFactory::createCustomer($data['customer']) : null)
            ->setDomain(isset($data['domain']) ? DataModelFactory::createDomain($data['domain']) : null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    public static function createAccessClearance(array $data): AccessClearance
    {
        $model = new AccessClearance();
        $model->setGranted($data['granted'] ?? false)
            ->setType($data['type'] ?? AccessClearance::NO_ACCESS);

        return $model;
    }
}
