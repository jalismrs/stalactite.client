<?php
declare(strict_types=1);

namespace Test\Access\AuthToken;

use BadMethodCallException;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use OutOfBoundsException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * JwtFactoryTest
 *
 * @package Test\Access\AuthToken
 */
class JwtFactoryTest extends
    TestCase
{
    /**
     * testGenerateAuthTokenJwt
     *
     * @return void
     *
     * @throws BadMethodCallException
     * @throws OutOfBoundsException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testGenerateAuthTokenJwt(): void
    {
        $apiToken = 'fake api token';
        $token = JwtFactory::generateJwt($apiToken, 'client.test');

        $validation = new ValidationData();
        $validation->setAudience(JwtFactory::JWT_AUDIENCE);

        self::assertFalse($token->isExpired());
        self::assertTrue($token->hasClaim('challenge'));
        self::assertTrue($token->validate($validation));

        $challenge = $token->getClaim('challenge');
        $signer = new Sha256();

        self::assertTrue($token->verify($signer, $challenge . $apiToken));
    }
}
