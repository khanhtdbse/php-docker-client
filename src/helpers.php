<?php
namespace DockerClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

// Override laravel data_get for support get entity attributes

function data_get($target, $key, $default = null)
{
    if (is_null($key)) {
        return $target;
    }

    if ($target instanceof \DockerClient\Entities\Entity) {
        $target = (array)$target;
    }

    $key = is_array($key) ? $key : explode('.', $key);

    while (!is_null($segment = array_shift($key))) {
        if ($segment === '*') {
            if ($target instanceof Collection) {
                $target = $target->all();
            } elseif (!is_array($target)) {
                return value($default);
            }

            $result = Arr::pluck($target, $key);

            return in_array('*', $key) ? Arr::collapse($result) : $result;
        }

        if (Arr::accessible($target) && Arr::exists($target, $segment)) {
            $target = $target[$segment];
        } elseif (is_object($target) && isset($target->{$segment})) {
            $target = $target->{$segment};
        } else {
            return value($default);
        }
    }

    return $target;
}