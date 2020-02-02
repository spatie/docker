<?php

namespace Spatie\Docker;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DockerContainer
{
    public string $name = '';

    public string $image = '';

    public int $port = 4848;

    public static function new(): DockerContainer
    {
        return new self();
    }

    public function named(string $name): DockerContainer
    {
        $this->name = $name;

        return $this;
    }

    public function image(string $image): DockerContainer
    {
        $this->image = $image;

        return $this;
    }

    public function port(int $port): DockerContainer
    {
        $this->port = $port;

        return $this;
    }

    public function start()
    {
        $name = $this->name . '-' . substr(uniqid(), 0, 8);

        $command = "docker run -p {$this->port}:22 --name {$name} -d --rm {$this->image}";

        $process = Process::fromShellCommandline($command);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return new DockerContainerInstance(
            $this,
            $process->getOutput(),
            $name
        );
    }
}
