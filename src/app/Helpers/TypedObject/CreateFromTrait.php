<?php

namespace App\Helpers\TypedObject;

trait CreateFromTrait
{
    public static function createFrom(object $obj) : object
    {
        $result = self::createInstance();
        $stub = static::getStub();
        foreach ($obj as $propName => $value)
        {
            /** @var Contract $class */
            if (isset($stub->{$propName})) {
                if (is_object($stub->{$propName})) {
                    $class = get_class($stub->{$propName});
                    $result->{$propName} = $value ? $class::createFrom($value) : null;
                } else if (is_array($stub->{$propName})) {
                    $class = get_class($stub->{$propName}[0]);
                    $result->{$propName} = [];
                    foreach ($value as $idx => $item) {
                        $result->{$propName}[$idx] = $item ? $class::createFrom($item) : null;
                    }
                }
            } else {
                $result->{$propName} = $value;
            }
        }
        return $result;
    }

    private static function createInstance(): Contract
    {
        return (new \ReflectionClass(static::class))->newInstanceWithoutConstructor();
    }

    public static function getStub(): Contract
    {
        return self::createInstance();
    }
}
