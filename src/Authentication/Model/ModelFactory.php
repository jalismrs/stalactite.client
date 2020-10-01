<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication\Model;

abstract class ModelFactory
{
    public static function createClientApp(array $data): ClientApp
    {
        $model = new ClientApp();
        $model
            ->setGoogleOAuthClientId($data['googleOAuthClientId'] ?? null)
            ->setName($data['name'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }
}
