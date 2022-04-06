<?php

namespace App\Services;

use App\interfaces\CacheInterface;

class Cache implements CacheInterface
{
    public function set(string $key, $value, int $duration = 60 * 5)
    {
        file_put_contents($key, \json_encode($value));
    }

    public function get(string $key)
    {
        if (file_exists($key)) {
            $fileContents = file_get_contents($key);
            return \json_decode($fileContents, true);
        } else {
            return null;
        }
    }
}
