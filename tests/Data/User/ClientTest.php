<?php
declare(strict_types=1);

namespace Test\Data\User;

use Jalismrs\Stalactite\Client\Data\User\Client;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Symfony\Component\HttpClient\MockHttpClient;
use Test\ClientTestTrait;

/**
 * ClientTest
 *
 * @package Test\Data\User
 */
class ClientTest extends
    TestCase
{
    use ClientTestTrait;

    /**
     * testCertificationGraduation
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testCertificationGraduation(): void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->certificationGraduations();
        $client2 = $baseClient->certificationGraduations();

        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }

    /**
     * testLead
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testLead(): void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->leads();
        $client2 = $baseClient->leads();

        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }

    /**
     * testMe
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testMe(): void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->me();
        $client2 = $baseClient->me();

        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }

    /**
     * testPhoneLine
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testPhoneLine(): void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->phoneLines();
        $client2 = $baseClient->phoneLines();

        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }

    /**
     * testPost
     *
     * @return void
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testPost(): void
    {
        $baseClient = new Client(
            'http://fakeHost',
            null,
            new MockHttpClient()
        );

        $client1 = $baseClient->posts();
        $client2 = $baseClient->posts();

        self::checkClients(
            $baseClient,
            $client1,
            $client2
        );
    }
}
