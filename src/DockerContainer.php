<?php

namespace Spatie\Docker;

use Spatie\Docker\Exceptions\CouldNotStartDockerContainer;
use Spatie\Macroable\Macroable;
use Symfony\Component\Process\Process;

class DockerContainer
{
    use Macroable;

    public string $image = '';

    public string $name = '';

    public bool $daemonize = true;

    public bool $privileged = false;

    public string $shell = 'bash';

    public ?string $network = null;

    /** @var PortMapping[] */
    public array $portMappings = [];

    /** @var EnvironmentMapping[] */
    public array $environmentMappings = [];

    /** @var VolumeMapping[] */
    public array $volumeMappings = [];

    /** @var LabelMapping[] */
    public array $labelMappings = [];

    public bool $cleanUpAfterExit = true;

    public bool $stopOnDestruct = false;

    public string $remoteHost = '';

    public string $command = '';

    public array $optionalArgs = [];

    public function __construct(string $image, string $name = '')
    {
        $this->image = $image;

        $this->name = $name;
    }

    public static function create(...$args): self
    {
        return new static(...$args);
    }

    public function image(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function daemonize(bool $daemonize = true): self
    {
        $this->daemonize = $daemonize;

        return $this;
    }

    public function privileged(bool $privileged = true): self
    {
        $this->privileged = $privileged;

        return $this;
    }

    public function shell(string $shell): self
    {
        $this->shell = $shell;

        return $this;
    }

    public function network(?string $network): self
    {
        $this->network = $network;

        return $this;
    }

    public function doNotDaemonize(): self
    {
        $this->daemonize = false;

        return $this;
    }

    public function cleanUpAfterExit(bool $cleanUpAfterExit): self
    {
        $this->cleanUpAfterExit = $cleanUpAfterExit;

        return $this;
    }

    public function doNotCleanUpAfterExit(): self
    {
        $this->cleanUpAfterExit = false;

        return $this;
    }

    /**
     * @param int|string $portOnHost
     */
    public function mapPort($portOnHost, int $portOnDocker): self
    {
        $this->portMappings[] = new PortMapping($portOnHost, $portOnDocker);

        return $this;
    }

    public function setEnvironmentVariable(string $envName, string $envValue): self
    {
        $this->environmentMappings[] = new EnvironmentMapping($envName, $envValue);

        return $this;
    }

    public function setVolume(string $pathOnHost, string $pathOnDocker): self
    {
        $this->volumeMappings[] = new VolumeMapping($pathOnHost, $pathOnDocker);

        return $this;
    }

    public function setLabel(string $labelName, string $labelValue): self
    {
        $this->labelMappings[] = new LabelMapping($labelName, $labelValue);

        return $this;
    }

    public function setOptionalArgs(...$args): self
    {
        $this->optionalArgs = $args;

        return $this;
    }

    public function stopOnDestruct(bool $stopOnDestruct = true): self
    {
        $this->stopOnDestruct = $stopOnDestruct;

        return $this;
    }

    public function remoteHost(string $remoteHost): self
    {
        $this->remoteHost = $remoteHost;

        return $this;
    }

    public function command(string $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getBaseCommand(): string
    {
        $baseCommand = [
            'docker',
            ...$this->getExtraDockerOptions(),
        ];

        return implode(' ', $baseCommand);
    }

    public function getStartCommand(): string
    {
        $startCommand = [
            $this->getBaseCommand(),
            'run',
            ...$this->getExtraOptions(),
            $this->image,
        ];

        if ($this->command !== '') {
            $startCommand[] = $this->command;
        }

        return implode(' ', $startCommand);
    }

    public function getStopCommand(string $dockerIdentifier): string
    {
        $stopCommand = [
            $this->getBaseCommand(),
            'stop',
            $dockerIdentifier,
        ];

        return implode(' ', $stopCommand);
    }

    public function getExecCommand(string $dockerIdentifier, string $command): string
    {
        $execCommand = [
            "echo \"{$command}\"",
            '|',
            $this->getBaseCommand(),
            'exec',
            '--interactive',
            $dockerIdentifier,
            $this->shell,
            '-',
        ];

        return implode(' ', $execCommand);
    }

    public function getCopyCommand(string $dockerIdentifier, string $fileOrDirectoryOnHost, string $pathInContainer): string
    {
        $copyCommand = [
            $this->getBaseCommand(),
            'cp',
            $fileOrDirectoryOnHost,
            "{$dockerIdentifier}:{$pathInContainer}",
        ];

        return implode(' ', $copyCommand);
    }

    public function getInspectCommand(string $dockerIdentifier): string
    {
        $execCommand = [
            $this->getBaseCommand(),
            'inspect',
            $dockerIdentifier,
        ];

        return implode(' ', $execCommand);
    }

    public function start(): DockerContainerInstance
    {
        $command = $this->getStartCommand();

        $process = Process::fromShellCommandline($command);

        $process->run();

        if (! $process->isSuccessful()) {
            throw CouldNotStartDockerContainer::processFailed($this, $process);
        }

        $dockerIdentifier = trim($process->getOutput());

        return new DockerContainerInstance(
            $this,
            $dockerIdentifier,
            $this->name,
        );
    }

    protected function getExtraOptions(): array
    {
        $extraOptions = [];

        if ($this->optionalArgs) {
            $extraOptions[] = implode(' ', $this->optionalArgs);
        }

        if (count($this->portMappings)) {
            $extraOptions[] = implode(' ', $this->portMappings);
        }

        if (count($this->environmentMappings)) {
            $extraOptions[] = implode(' ', $this->environmentMappings);
        }

        if (count($this->volumeMappings)) {
            $extraOptions[] = implode(' ', $this->volumeMappings);
        }

        if (count($this->labelMappings)) {
            $extraOptions[] = implode(' ', $this->labelMappings);
        }

        if ($this->name !== '') {
            $extraOptions[] = "--name {$this->name}";
        }

        if ($this->daemonize) {
            $extraOptions[] = '-d';
        }

        if ($this->privileged) {
            $extraOptions[] = '--privileged';
        }

        if ($this->cleanUpAfterExit) {
            $extraOptions[] = '--rm';
        }

        if ($this->network) {
            $extraOptions[] = '--network ' . $this->network;
        }

        return $extraOptions;
    }

    protected function getExtraDockerOptions(): array
    {
        $extraDockerOptions = [];

        if ($this->remoteHost !== '') {
            $extraDockerOptions[] = "-H {$this->remoteHost}";
        }

        return $extraDockerOptions;
    }
}
