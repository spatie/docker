<?php

namespace Spatie\Docker;

class Option
{

    protected string $optionName;

    /**
     * @var int|string
     */
    private $optionValue;

    public function __construct(string $optionName, $optionValue)
    {
        $this->optionName = $optionName;
        $this->optionValue = $optionValue;
    }

    public function __toString()
    {
        return "--{$this->optionName}={$this->optionValue}";
    }
}
