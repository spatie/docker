<?php

namespace Spatie\Docker;

class PortMapping
{
    private int $portOnHost;

    private int $portOnDocker;

    public function __construct(int $portOnHost = -1, int $portOnDocker = -1)
    {
        $this->portOnHost = $portOnHost;

        $this->portOnDocker = $portOnDocker;
    }

    public function __toString()
    {
        if ( -1 == $this->portOnHost || -1 == $this->portOnDocker ) {
            return "-P ";
        } else {
            return "-p {$this->portOnHost}:{$this->portOnDocker}";
        }
    }

    public function getPortOnHost(): int
    {
        return $this->portOnHost;
    }

    public function getPortOnDocker(): int
    {
        return $this->portOnDocker;
    }
}
