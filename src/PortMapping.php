<?php

namespace Spatie\Docker;

class PortMapping
{
    /** @var int|string */
    private $portOnHost;

    private int $portOnDocker;

    private string $protocol;

    /**
     * @param int|string $portOnHost
     */
    public function __construct($portOnHost, int $portOnDocker, string $protocol = 'tcp')
    {
        $this->portOnHost = $portOnHost;

        $this->portOnDocker = $portOnDocker;

        $this->protocol = $protocol;
    }

    public function __toString()
    {
        return "-p {$this->portOnHost}:{$this->portOnDocker}/{$this->protocol}";
    }
}
