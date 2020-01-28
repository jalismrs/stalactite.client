<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

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
