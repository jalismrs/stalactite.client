<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

use function array_map;
use function array_walk;
use function get_class;
use function gettype;
use function is_a;
use function is_object;

/**
 * path
 *
 * @param string $path
 * @param bool   $real
 *
 * @return string
 */
function path(string $path, bool $real = false) : ?string
{
    $rootDir = __DIR__ . '/..';
    
    $path = "{$rootDir}/{$path}";
    $path = preg_replace('#[/\\\]+#', DIRECTORY_SEPARATOR, $path);
    
    if ($real) {
        $result = realpath($path)
            ?: null;
    } else {
        $result = $path;
    }
    
    return $result;
}

/**
 * getUid
 *
 * @param             $model
 * @param string|null $class
 *
 * @return null|string
 *
 * @throws \Jalismrs\Stalactite\Client\ClientException
 */
function getUid($model, string $class = null) : ?string
{
    if (!$model instanceof AbstractModel) {
        $actual = is_object($model)
            ? get_class($model)
            : gettype($model);
        
        throw new ClientException(
            "parameter must be a model, {$actual} given",
            ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
        );
    }
    
    $expected = $class ?? AbstractModel::class;
    if (!is_a($model, $expected)) {
        $actual = get_class($model);
        
        throw new ClientException(
            "model parameter must be a {$expected} model, {$actual} given",
            ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
        );
    }
    
    return $model->getUid();
}

/**
 * getUids
 *
 * @param array       $models
 * @param string|null $class
 *
 * @return array
 *
 * @throws \Jalismrs\Stalactite\Client\ClientException
 */
function getUids(array $models, string $class = null) : array
{
    $uids = [];
    
    foreach($models as $model) {
        $uids[] = getUid($model, $class);
    }
    
    return $uids;
}
