<?php

namespace Spatie\Docker;

class OptionMapping
{

    private string $optionName;

    /**
     * @var int|string
     */
    private $optionValue;

    /**
     * ArgumentMapping constructor.
     *
     * @param string $optionName
     * @param int|string $optionValue
     */
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
