<?php

namespace Spatie\Docker;

class EnvironmentMapping
{
    private string $name;

    private string $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;

        $this->value = $value;
    }

    public function __toString()
    {
        return "-e {$this->name}={$this->value}";
    }
}
