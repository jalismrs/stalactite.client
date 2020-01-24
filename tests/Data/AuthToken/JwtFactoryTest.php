<?php
declare(strict_types=1);

namespace Test\Data\AuthToken;

use BadMethodCallException;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use OutOfBoundsException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * JwtFactoryTest
 *
 * @package Test\Data\AuthToken
 */
class JwtFactoryTest extends
    TestCase
{
    /**
     * testGenerateAuthTokenJwt
     *
     * Those are the ways the API checks the JWT
     * Since the API token here is invalid, the forged JWT will be invalid for the API but should still has a valid
     * format and signature
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
