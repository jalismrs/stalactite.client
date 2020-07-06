<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

/**
 * Class ModelFactory
 *
 * @package Jalismrs\Stalactite\Service\Data\Model
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

    /**
     * createPostModel
     *
     * @static
     *
     * @param array $data
     *
     * @return Post
     */
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

    /**
     * createDomainModel
     *
     * @static
     *
     * @param array $data
     *
     * @return Domain
     */
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

    /**
     * createCustomerModel
     *
     * @static
     *
     * @param array $data
     *
     * @return Customer
     */
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

    /**
     * @param array $data
     * @return Permission
     */
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
}
