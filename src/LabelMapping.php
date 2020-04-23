<?php

namespace Spatie\Docker;

class LabelMapping
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
        return "-l {$this->name}={$this->value}";
    }
}
