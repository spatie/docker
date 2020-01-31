<?php

namespace Spatie\Docker\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Docker\Docker;
use Spatie\Docker\DockerContainer;
use Spatie\Docker\DockerContainerInstance;

class ExampleTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        /**
         * First start the docker daemon, should be included in the package but that isn't yet done
         * Then create a Spatie docker instance as such `docker build -t spatie/dock .`
         */

        $docker = Docker::new();

        $container = DockerContainer::new()
            ->named('Spatie')
            ->port(4848)
            ->image('spatie/dock')
            ->withAuthorizedKey('YOUR_KEY');

        $containerInstance = $docker->start($container);

    }
}
