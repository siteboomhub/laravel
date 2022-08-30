<?php

namespace App\Services\League\ValueObjects;

class Uid
{
    public readonly string $value;

    public function __construct(string $uid)
    {
        $this->value = $uid;
    }

    public function equals(Uid $uid): bool
    {
        return $uid->value === $this->value;
    }
}
