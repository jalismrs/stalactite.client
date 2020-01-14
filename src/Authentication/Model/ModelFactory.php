<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\Authentication\Model;

/**
 * Class ModelFactory
 * @package jalismrs\Stalactite\Client\Authentication\Model
 * Factory to instantiate models from arrays
 */
abstract class ModelFactory
{
    public static function createTrustedApp(array $data): TrustedApp
    {
        $ta = new TrustedApp();
        $ta->setName($data['name'] ?? null)
            ->setResetToken($data['resetToken'] ?? null)
            ->setAuthToken($data['authToken'] ?? null)
            ->setGoogleOAuthClientId($data['googleOAuthClientId'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $ta;
    }
}
