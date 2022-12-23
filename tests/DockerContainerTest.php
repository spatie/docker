<?php

namespace Spatie\Docker\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Docker\DockerContainer;

class DockerContainerTest extends TestCase
{
    private DockerContainer $container;

    public function setUp(): void
    {
        parent::setUp();

        $this->container = new DockerContainer('spatie/docker');
    }

    /** @test */
    public function it_will_daemonize_and_clean_up_the_container_by_default()
    {
        $command = $this->container->getStartCommand();

        $this->assertEquals('docker run -d --rm spatie/docker', $command);
    }

    /** @test */
    public function it_can_instantiate_via_the_create_method()
    {
        $this->assertInstanceOf(DockerContainer::class, DockerContainer::create('spatie/docker'));
    }

    /** @test */
    public function it_can_not_be_daemonized()
    {
        $command = $this->container
            ->doNotDaemonize()
            ->getStartCommand();

        $this->assertEquals('docker run --rm spatie/docker', $command);
    }

    /** @test */
    public function it_can_be_privileged()
    {
        $command = $this->container
            ->privileged()
            ->getStartCommand();

        $this->assertEquals('docker run -d --privileged --rm spatie/docker', $command);
    }

    /** @test */
    public function it_can_not_be_cleaned_up()
    {
        $command = $this->container
            ->doNotCleanUpAfterExit()
            ->getStartCommand();

        $this->assertEquals('docker run -d spatie/docker', $command);
    }

    /** @test * */
    public function it_can_be_named()
    {
        $command = $this->container
            ->name('my-name')
            ->getStartCommand();

        $this->assertEquals('docker run --name my-name -d --rm spatie/docker', $command);
    }

    /** @test */
    public function it_can_map_ports()
    {
        $command = $this->container
            ->mapPort(4848, 22)
            ->mapPort(9000, 21)
            ->getStartCommand();

        $this->assertEquals('docker run -p 4848:22 -p 9000:21 -d --rm spatie/docker', $command);
    }

    /** @test */
    public function it_can_map_string_ports()
    {
        $command = $this->container
            ->mapPort('127.0.0.1:4848', 22)
            ->mapPort('0.0.0.0:9000', 21)
            ->getStartCommand();

        $this->assertEquals('docker run -p 127.0.0.1:4848:22 -p 0.0.0.0:9000:21 -d --rm spatie/docker', $command);
    }

    /** @test */
    public function it_can_set_environment_variables()
    {
        $command = $this->container
            ->setEnvironmentVariable('NAME', 'VALUE')
            ->setEnvironmentVariable('NAME2', 'VALUE2')
            ->getStartCommand();

        $this->assertEquals('docker run -e NAME=VALUE -e NAME2=VALUE2 -d --rm spatie/docker', $command);
    }

    /** @test */
    public function it_can_set_volumes()
    {
        $command = $this->container
            ->setVolume('/on/my/host', '/on/my/container')
            ->setVolume('/data', '/data')
            ->getStartCommand();

        $this->assertEquals(
            'docker run -v /on/my/host:/on/my/container -v /data:/data -d --rm spatie/docker',
            $command
        );
    }

    /** @test */
    public function it_can_set_labels()
    {
        $command = $this->container
            ->setLabel('traefik.enable', 'true')
            ->setLabel('foo', 'bar')
            ->setLabel('name', 'spatie')
            ->getStartCommand();

        $this->assertEquals(
            'docker run -l traefik.enable=true -l foo=bar -l name=spatie -d --rm spatie/docker',
            $command
        );
    }

    /** @test */
    public function it_can_set_optional_args()
    {
        $command = $this->container
            ->setOptionalArgs('-it', '-a', '-i', '-t')
            ->getStartCommand();

        $this->assertEquals('docker run -it -a -i -t -d --rm spatie/docker', $command);
    }

    /** @test */
    public function it_can_set_commands()
    {
        $command = $this->container
            ->setCommands('--api.insecure=true', '--entrypoints.web.address=:80')
            ->getStartCommand();

        $this->assertEquals('docker run -d --rm spatie/docker --api.insecure=true --entrypoints.web.address=:80', $command);
    }

    /** @test */
    public function it_can_set_network()
    {
        $command = $this->container
            ->network('my-network')
            ->getStartCommand();

        $this->assertEquals('docker run -d --rm --network my-network spatie/docker', $command);
    }

    /** @test */
    public function it_can_use_remote_docker_host()
    {
        $command = $this->container
            ->remoteHost('ssh://username@host')
            ->getStartCommand();

        $this->assertEquals('docker -H ssh://username@host run -d --rm spatie/docker', $command);
    }

    /** @test */
    public function it_can_execute_command_at_start()
    {
        $command = $this->container
            ->command('whoami')
            ->getStartCommand();

        $this->assertEquals('docker run -d --rm spatie/docker whoami', $command);
    }

    /** @test */
    public function it_can_generate_stop_command()
    {
        $command = $this->container
            ->getStopCommand('abcdefghijkl');

        $this->assertEquals('docker stop abcdefghijkl', $command);
    }

    /** @test */
    public function it_can_generate_stop_command_with_remote_host()
    {
        $command = $this->container
            ->remoteHost('ssh://username@host')
            ->getStopCommand('abcdefghijkl');

        $this->assertEquals('docker -H ssh://username@host stop abcdefghijkl', $command);
    }

    /** @test */
    public function it_can_generate_exec_command()
    {
        $command = $this->container
            ->getExecCommand('abcdefghijkl', 'whoami');

        $this->assertEquals('echo "whoami" | docker exec --interactive abcdefghijkl bash -', $command);
    }

    /** @test */
    public function it_can_generate_exec_command_with_remote_host()
    {
        $command = $this->container
            ->remoteHost('ssh://username@host')
            ->getExecCommand('abcdefghijkl', 'whoami');

        $this->assertEquals(
            'echo "whoami" | docker -H ssh://username@host exec --interactive abcdefghijkl bash -',
            $command
        );
    }

    /** @test */
    public function it_can_generate_exec_command_with_custom_shell()
    {
        $command = $this->container
            ->shell('sh')
            ->getExecCommand('abcdefghijkl', 'whoami');

        $this->assertEquals('echo "whoami" | docker exec --interactive abcdefghijkl sh -', $command);
    }

    /** @test */
    public function it_can_generate_copy_command()
    {
        $command = $this->container
            ->getCopyCommand('abcdefghijkl', '/home/spatie', '/mnt/spatie');

        $this->assertEquals('docker cp /home/spatie abcdefghijkl:/mnt/spatie', $command);
    }

    /** @test */
    public function it_can_generate_copy_command_with_remote_host()
    {
        $command = $this->container
            ->remoteHost('ssh://username@host')
            ->getCopyCommand('abcdefghijkl', '/home/spatie', '/mnt/spatie');

        $this->assertEquals('docker -H ssh://username@host cp /home/spatie abcdefghijkl:/mnt/spatie', $command);
    }
}
