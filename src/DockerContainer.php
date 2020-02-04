<?php

namespace Spatie\Docker;

use Spatie\Macroable\Macroable;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DockerContainer
{
    use Macroable;

    public string $name = '';

    public string $image = '';

    /**
     * @var \Spatie\Docker\PortMapping[]
     */
    public array $portMappings = [];

    public bool $stopAfterCompletion = false;

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

    public function mapPort(int $portOnHost, $portOnDocker): DockerContainer
    {
        $this->portMappings[] = new PortMapping($portOnHost, $portOnDocker);

        return $this;
    }

    public function stopAfterCompletion(bool $stopAfterCompletion = true): self
    {
        $this->stopAfterCompletion = $stopAfterCompletion;

        return $this;
    }

    public function start()
    {
        $portMappings = implode(' ', $this->portMappings);

        $command = "docker run {$portMappings} --name {$this->name} -d --rm {$this->image}";

        $process = Process::fromShellCommandline($command);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $dockerIdentifier = $process->getOutput();

        return new DockerContainerInstance(
            $this,
            $dockerIdentifier,
            $this->name,
        );
    }
}
