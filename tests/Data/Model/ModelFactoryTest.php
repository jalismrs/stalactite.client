<?php

namespace Jalismrs\Stalactite\Client\Tests\Data\Model;

use Jalismrs\Stalactite\Client\Data\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class ModelFactoryTest
 *
 * @package Jalismrs\Stalactite\Client\Tests\Data\Model
 *
 * @covers \Jalismrs\Stalactite\Client\Data\Model\ModelFactory
 */
class ModelFactoryTest extends
    TestCase
{
    /**
     * testCreateAccessClearance
     *
     * @return void
     */
    public function testCreateAccessClearance(): void
    {
        // arrange
        $data = [];

        // act
        $output = ModelFactory::createAccessClearance($data);

        // assert
        self::assertFalse(
            $output->isGranted()
        );
        self::assertSame(
            AccessClearance::NO_ACCESS,
            $output->getType()
        );
    }

    /**
     * testCreateCustomer
     *
     * @return void
     */
    public function testCreateCustomer(): void
    {
        // arrange
        $data = [];

        // act
        $output = ModelFactory::createCustomer($data);

        // assert
        self::assertNull(
            $output->getEmail()
        );
        self::assertNull(
            $output->getFirstName()
        );
        self::assertNull(
            $output->getLastName()
        );
        self::assertNull(
            $output->getUid()
        );
    }

    /**
     * testCreateDomain
     *
     * @return void
     */
    public function testCreateDomain(): void
    {
        // arrange
        $data = [];

        // act
        $output = ModelFactory::createDomain($data);

        // assert
        self::assertNull(
            $output->getApiKey()
        );
        self::assertFalse(
            $output->hasExternalAuth()
        );
        self::assertNull(
            $output->getGenerationDate()
        );
        self::assertNull(
            $output->getName()
        );
        self::assertNull(
            $output->getType()
        );
        self::assertNull(
            $output->getUid()
        );
    }

    /**
     * testCreateDomainCustomerRelation
     *
     * @return void
     */
    public function testCreateDomainCustomerRelation(): void
    {
        // arrange
        $data = [];

        // act
        $output = ModelFactory::createDomainCustomerRelation($data);

        // assert
        self::assertNull(
            $output->getCustomer()
        );
        self::assertNull(
            $output->getDomain()
        );
        self::assertNull(
            $output->getUid()
        );
    }

    /**
     * testCreateDomainUserRelation
     *
     * @return void
     */
    public function testCreateDomainUserRelation(): void
    {
        // arrange
        $data = [];

        // act
        $output = ModelFactory::createDomainUserRelation($data);

        // assert
        self::assertNull(
            $output->getDomain()
        );
        self::assertNull(
            $output->getUid()
        );
        self::assertNull(
            $output->getUser()
        );
    }

    /**
     * testCreatePermission
     *
     * @return void
     */
    public function testCreatePermission(): void
    {
        // arrange
        $data = [];

        // act
        $output = ModelFactory::createPermission($data);

        // assert
        self::assertNull(
            $output->getOperation()
        );
        self::assertNull(
            $output->getResource()
        );
        self::assertNull(
            $output->getScope()
        );
        self::assertNull(
            $output->getUid()
        );
    }

    /**
     * testCreatePost
     *
     * @return void
     */
    public function testCreatePost(): void
    {
        // arrange
        $data = [
            'permissions' => [
                [],
            ],
        ];

        // act
        $output = ModelFactory::createPost($data);

        // assert
        self::assertNull(
            $output->getName()
        );
        self::assertNull(
            $output->getShortName()
        );
        self::assertNull(
            $output->getUid()
        );
        self::assertCount(
            1,
            $output->getPermissions()
        );
    }

    /**
     * testCreateUser
     *
     * @return void
     */
    public function testCreateUser(): void
    {
        // arrange
        $data = [
            'leads' => [
                [],
            ],
            'posts' => [
                [],
            ],
        ];

        // act
        $output = ModelFactory::createUser($data);

        // assert
        self::assertFalse(
            $output->isAdmin()
        );
        self::assertNull(
            $output->getEmail()
        );
        self::assertNull(
            $output->getFirstName()
        );
        self::assertNull(
            $output->getLastName()
        );
        self::assertNull(
            $output->getUid()
        );
        self::assertCount(
            1,
            $output->getLeads()
        );
        self::assertCount(
            1,
            $output->getPosts()
        );
    }
}
