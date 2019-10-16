<?php

namespace jalismrs\Stalactite\Client\DataManagement\Model;

/**
 * Class ModelFactory
 * @package jalismrs\Stalactite\Client\DataManagement\Model
 * Factory to instantiate models from arrays
 */
abstract class ModelFactory
{
    /**
     * @param array $data
     * @return User
     */
    public static function createUser(array $data): User
    {
        $user = new User();
        $user->setGoogleId($data['googleId'] ?? null)
            ->setLastName($data['lastName'] ?? null)
            ->setFirstName($data['firstName'] ?? null)
            ->setEmail($data['email'] ?? null)
            ->setAdmin($data['admin'] ?? false)
            ->setBirthday($data['birthday'] ?? null)
            ->setOffice($data['office'] ?? null)
            ->setLocation($data['location'] ?? null)
            ->setGender($data['gender'] ?? null)
            ->setUid($data['uid'] ?? null);

        if (isset($data['phoneLines'])) {
            foreach ($data['phoneLines'] as $phoneLine) {
                $user->addPhoneLine(self::createPhoneLine($phoneLine));
            }
        }

        if (isset($data['certifications'])) {
            foreach ($data['certifications'] as $certification) {
                $user->addCertification(self::createCertificationGraduation($certification));
            }
        }

        if (isset($data['posts'])) {
            foreach ($data['posts'] as $post) {
                $user->addPost(self::createPost($post));
            }
        }

        if (isset($data['leads'])) {
            foreach ($data['leads'] as $lead) {
                $user->addLead(self::createPost($lead));
            }
        }

        return $user;
    }

    /**
     * @param array $data
     * @return Domain
     */
    public static function createDomain(array $data): Domain
    {
        $domain = new Domain();
        $domain->setName($data['name'] ?? null)
            ->setGenerationDate($data['generationDate'] ?? null)
            ->setExternalAuth($data['externalAuth'] ?? false)
            ->setApiKey($data['apiKey'] ?? null)
            ->setType($data['type'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $domain;
    }

    /**
     * @param array $data
     * @return Customer
     */
    public static function createCustomer(array $data): Customer
    {
        $customer = new Customer();
        $customer->setEmail($data['email'] ?? null)
            ->setFirstName($data['firstName'] ?? null)
            ->setLastName($data['lastName'] ?? null)
            ->setGoogleId($data['googleId'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $customer;
    }

    /**
     * @param array $data
     * @return Post
     */
    public static function createPost(array $data): Post
    {
        $post = new Post();
        $post->setName($data['name'] ?? null)
            ->setPrivilege($data['privilege'] ?? null)
            ->setShortName($data['shortName'] ?? null)
            ->setRank($data['rank'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $post;
    }

    /**
     * @param array $data
     * @return CertificationType
     */
    public static function createCertificationType(array $data): CertificationType
    {
        $ct = new CertificationType();
        $ct->setName($data['name'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $ct;
    }

    /**
     * @param array $data
     * @return CertificationGraduation
     */
    public static function createCertificationGraduation(array $data): CertificationGraduation
    {
        $cg = new CertificationGraduation();
        $cg->setType($data['type'] ? self::createCertificationType($data['type']) : null)
            ->setDate($data['date'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $cg;
    }

    /**
     * @param array $data
     * @return PhoneType
     */
    public static function createPhoneType(array $data): PhoneType
    {
        $pt = new PhoneType();
        $pt->setName($data['name'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $pt;
    }

    /**
     * @param array $data
     * @return PhoneLine
     */
    public static function createPhoneLine(array $data): PhoneLine
    {
        $pl = new PhoneLine();
        $pl->setType($data['type'] ? self::createPhoneType($data['type']) : null)
            ->setValue($data['value'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $pl;
    }
}