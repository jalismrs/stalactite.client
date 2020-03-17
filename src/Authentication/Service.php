<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication;

use BadMethodCallException;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\TrustedApp\Service as TrustedAppService;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\JwtException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Throwable;
use function in_array;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Authentication
 */
class Service extends AbstractService
{
    public const JWT_ISSUER = 'stalactite.auth-api';

    private const AUTHORIZED_JWT_TYPES = [
        'user',
        'customer'
    ];

    /**
     * @var TrustedAppService|null
     */
    private ?TrustedAppService $serviceTrustedApp = null;

    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * trustedApps
     *
     * @return TrustedAppService
     */
    public function trustedApps(): TrustedAppService
    {
        if ($this->serviceTrustedApp === null) {
            $this->serviceTrustedApp = new TrustedAppService($this->getClient());
        }

        return $this->serviceTrustedApp;
    }

    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * @param string $jwt
     * @return bool
     * @throws ClientException
     * @throws JwtException
     */
    public function validateJwt(string $jwt): bool
    {
        try {
            $token = self::getTokenFromString($jwt);
        } catch (Throwable $t) {
            $this->getLogger()->error($t);
            throw new JwtException('Invalid user JWT', JwtException::INVALID_JWT_STRING, $t);
        }

        $data = new ValidationData();
        $data->setIssuer(self::JWT_ISSUER);

        if (
            !$token->hasClaim('iss')
            || !$token->hasClaim('aud')
            || !$token->hasClaim('type')
            || !$token->hasClaim('sub')
            || !$token->hasClaim('iat')
            || !$token->hasClaim('exp')
        ) {
            $exception = new JwtException('Invalid JWT structure', JwtException::INVALID_JWT_STRUCTURE);
            $this->getLogger()->error($exception);
            throw $exception;
        }

        if ($token->isExpired()) {
            $exception = new JwtException('Expired JWT', JwtException::EXPIRED_JWT);
            $this->getLogger()->error($exception);
            throw $exception;
        }

        if (!$token->hasClaim('type') || !in_array($token->getClaim('type'), self::AUTHORIZED_JWT_TYPES, true)) {
            $exception = new JwtException('Invalid JWT user type', JwtException::INVALID_JWT_USER_TYPE);
            $this->getLogger()->error($exception);
            throw $exception;
        }

        if (!$token->validate($data)) {
            $exception = new JwtException('Invalid JWT issuer', JwtException::INVALID_JWT_ISSUER);
            $this->getLogger()->error($exception);
            throw $exception;
        }

        $signer = new Sha256();
        $publicKey = new Key($this->getRSAPublicKey()->getBody());

        try {
            $validSignature = $token->verify($signer, $publicKey);
        } catch (BadMethodCallException $badMethodCallException) {
            $this->getLogger()->error($badMethodCallException);
            // thrown by the library on invalid key
            throw new JwtException('Missing JWT signature', JwtException::MISSING_JWT_SIGNATURE, $badMethodCallException);
        } catch (InvalidArgumentException $invalidArgumentException) {
            $this->getLogger()->error($invalidArgumentException);
            // thrown by the library on invalid key
            throw new JwtException('Invalid RSA public key', JwtException::INVALID_STALACTITE_RSA_PUBLIC_KEY, $invalidArgumentException);
        } catch (Throwable $t) { // other exceptions result in an invalid token / signature
            $this->getLogger()->error($t);
            throw new JwtException('Invalid JWT signature', JwtException::INVALID_JWT_SIGNATURE, $t);
        }

        if (!$validSignature) {
            throw new JwtException('Invalid JWT signature', JwtException::INVALID_JWT_SIGNATURE);
        }

        return true;
    }

    /**
     * @param string $jwt
     * @return Token
     */
    private static function getTokenFromString(string $jwt): Token
    {
        return (new Parser())->parse($jwt);
    }

    /**
     * @return Response
     * @throws ClientException
     */
    public function getRSAPublicKey(): Response
    {
        $endpoint = new Endpoint('/auth/publicKey');
        return $this->getClient()->request($endpoint);
    }

    /**
     * @param TrustedApp $trustedAppModel
     * @param string $userGoogleJwt
     * @return Response
     * @throws ClientException
     */
    public function login(TrustedApp $trustedAppModel, string $userGoogleJwt): Response
    {
        $endpoint = new Endpoint('/auth/login', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema([
            'jwt' => ['type' => JsonRule::STRING_TYPE]
        ]));

        return $this
            ->getClient()
            ->request($endpoint, [
                'json' => [
                    'appName' => $trustedAppModel->getName(),
                    'appToken' => $trustedAppModel->getAuthToken(),
                    'userGoogleJwt' => $userGoogleJwt,
                ]
            ]);
    }
}
