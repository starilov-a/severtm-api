<?php

namespace App\Modules\UserCabinet\Infrastructure\Traits;


use App\Modules\Common\Infrastructure\Traits\Traversable;

trait ArrayableTrait
{
    public function toArray(array $opts = []): array
    {
        $opts += [
            'snake_case'    => false,
            'include_nulls' => false,
            'date_format'   => \DATE_ATOM,
        ];

        $ref = new \ReflectionObject($this);
        $data = [];

        foreach ($ref->getProperties() as $prop) {
            if ($prop->isStatic()) { continue; }
            if (method_exists($prop, 'isInitialized') && !$prop->isInitialized($this)) { continue; }

            $prop->setAccessible(true);
            $key = $prop->getName();
            $val = $this->normalizeValue($prop->getValue($this), $opts);

            if ($val === null && !$opts['include_nulls']) { continue; }
            if ($opts['snake_case']) {
                $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
            }
            $data[$key] = $val;
        }

        return $data;
    }

    private function normalizeValue(mixed $value, array $opts): mixed
    {
        if ($value === null || is_scalar($value)) { return $value; }
        if ($value instanceof \DateTimeInterface)   { return $value->format($opts['date_format']); }
        if ($value instanceof \BackedEnum)          { return $value->value; }
        if ($value instanceof \JsonSerializable)   { return $value->jsonSerialize(); }

        // вложенные DTO с таким же трейтом
        if (method_exists($value, 'toArray')) {
            return $value->toArray($opts);
        }

        if (is_array($value) || $value instanceof Traversable) {
            $out = [];
            foreach ($value as $k => $v) { $out[$k] = $this->normalizeValue($v, $opts); }
            return $out;
        }

        $guess = ['get'.ucfirst((string)$value), 'is'.ucfirst((string)$value)];
        foreach ($guess as $m) {
            if (is_object($value) && method_exists($value, $m)) {
                return $this->normalizeValue($value->$m(), $opts);
            }
        }

        return (string)$value;
    }
}