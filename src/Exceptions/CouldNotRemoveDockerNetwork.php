<?php

namespace Spatie\Docker\Exceptions;

use Exception;
use Spatie\Docker\DockerContainer;
use Spatie\Docker\DockerNetwork;
use Symfony\Component\Process\Process;

class CouldNotRemoveDockerNetwork extends Exception
{
    public static function processFailed(DockerNetwork $network, Process $process)
    {
        return new static("Could not remove docker network `{$network->name}`. Process output: `{$process->getErrorOutput()}`");
    }
}
