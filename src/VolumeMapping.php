<?php

namespace Spatie\Docker;

class VolumeMapping
{
    private string $pathOnHost;

    private string $pathOnDocker;

    public function __construct(string $pathOnHost, string $pathOnDocker)
    {
        $this->pathOnHost = $pathOnHost;

        $this->pathOnDocker = $pathOnDocker;
    }

    public function __toString()
    {
        return "-v {$this->pathOnHost}:{$this->pathOnDocker}";
    }
}
