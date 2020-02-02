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
        echo DockerContainer::new()
            ->named('Spatie')
            ->port(4848)
            ->image('spatie/dock')
            ->start()
            ->addFiles(__DIR__ . '/keys/spatie_docker_package_id_rsa.pub', '/root/.ssh/authorized_keys')
            //->addPublicKey(file_get_contents(__DIR__ . '/keys/spatie_docker_package_id_rsa.pub'))
        ->getDockerIdentifier();
    }
}
