<?php

namespace Spatie\Docker\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Docker\Docker;
use Spatie\Docker\DockerContainer;
use Spatie\Ssh\Ssh;

class DockerTest extends TestCase
{
    private DockerContainer $container;

    public function setUp(): void
    {
        parent::setUp();

        $this->container = DockerContainer::new()
            ->image('spatie/dock')
            ->named('spatie_docker_test')
            ->port(4848)
            ->stopAfterCompletion();
    }

    /** @test */
    public function it_can_start_a_container()
    {
        $container = $this->container->start();

        $this->assertGreaterThan(0, strlen($container->getDockerIdentifier()));
    }

    /** @test */
    public function a_public_key_can_be_added_to_a_running_container()
    {
        $container = DockerContainer::new()
            ->image('spatie/dock')
            ->named('spatie_docker_test')
            ->port(4848)
            ->start()
            ->addPublicKey(file_get_contents('/Users/freek/.ssh/id_rsa.pub'));

        $process = (new Ssh('root', '0.0.0.0', 4848))->execute('whoami');

        $this->assertEquals('root', trim($process->getOutput()));

        $container->stop();
    }
}
