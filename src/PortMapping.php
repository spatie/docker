<?php

namespace Spatie\Docker;

class PortMapping
{
    /** @var int|string */
    private $portOnHost;

    private int $portOnDocker;

    /**
     * @param int|string $portOnHost
     */
    public function __construct($portOnHost, int $portOnDocker)
    {
        $this->portOnHost = $portOnHost;

        $this->portOnDocker = $portOnDocker;
    }

    public function __toString()
    {
        return "-p {$this->portOnHost}:{$this->portOnDocker}";
    }
}
