<?php

namespace Spatie\Docker;

class PortMapping
{
    private int $portOnHost;

    private int $portOnDocker;

    public function __construct(int $portOnHost, int $portOnDocker)
    {
        $this->portOnHost = $portOnHost;

        $this->portOnDocker = $portOnDocker;
    }

    public function __toString()
    {
        return "-p {$this->portOnHost}:{$this->portOnDocker}";
    }
}
