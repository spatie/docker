<?php

namespace Spatie\Docker;

use Spatie\Docker\Exceptions\CouldNotCreateDockerNetwork;
use Spatie\Docker\Exceptions\CouldNotRemoveDockerNetwork;
use Symfony\Component\Process\Process;

class DockerNetwork
{
    public string $name;

    public string $driver = 'bridge';

    public string $remoteHost;

    public array $optionalArgs = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function driver(string $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function setOptionalArgs(string ...$args): self
    {
        $this->optionalArgs = $args;

        return $this;
    }

    public function remoteHost(string $remoteHost): self
    {
        $this->remoteHost = $remoteHost;

        return $this;
    }

    public function getBaseCommand(): string
    {
        $baseCommand = [
            'docker',
            ...$this->getExtraDockerOptions(),
            'network'
        ];

        return implode(' ', $baseCommand);
    }

    public function getCreateCommand(): string
    {
        $createCommand = [
            $this->getBaseCommand(),
            'create',
            ...$this->getExtraOptions(),
            $this->name
        ];

        return implode(' ', $createCommand);
    }

    public function getRemoveCommand(): string
    {
        $removeCommand = [
            $this->getBaseCommand(),
            'rm',
            $this->name
        ];

        return implode(' ', $removeCommand);
    }

    public function getExistsCommand(): string
    {
        $existsCommand = [
            $this->getBaseCommand(),
            'inspect',
            $this->name
        ];

        return implode(' ', $existsCommand);
    }

    public function create(): self
    {
        $command = $this->getCreateCommand();

        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw CouldNotCreateDockerNetwork::processFailed($this, $process);
        }

        return $this;
    }

    public function remove(): self
    {
        $command = $this->getRemoveCommand();

        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw CouldNotRemoveDockerNetwork::processFailed($this, $process);
        }

        return $this;
    }

    public function exists(): bool
    {
        $command = $this->getExistsCommand();

        $process = Process::fromShellCommandline($command);
        $process->run();

        return $process->isSuccessful();
    }

    protected function getExtraOptions(): array
    {
        $extraOptions = [];

        if ($this->optionalArgs) {
            $extraOptions[] = implode(' ', $this->optionalArgs);
        }

        $extraOptions[] = '--driver ' . $this->driver;

        return $extraOptions;
    }

    protected function getExtraDockerOptions(): array
    {
        $extraDockerOptions = [];

        if (!empty($this->remoteHost)) {
            $extraDockerOptions[] = "-H {$this->remoteHost}";
        }

        return $extraDockerOptions;
    }
}
