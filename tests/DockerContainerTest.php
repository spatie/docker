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

    /** @test **/
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
    public function it_can_map_random_ports()
    {
        $command = $this->container
            ->mapRandomPorts()
            ->getStartCommand();

        $this->assertEquals('docker run -P -d --rm spatie/docker', $command);
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

        $this->assertEquals('docker run -v /on/my/host:/on/my/container -v /data:/data -d --rm spatie/docker', $command);
    }

    /** @test */
    public function it_can_set_labels()
    {
        $command = $this->container
            ->setLabel('traefik.enable', 'true')
            ->setLabel('foo', 'bar')
            ->setLabel('name', 'spatie')
            ->getStartCommand();

        $this->assertEquals('docker run -l traefik.enable=true -l foo=bar -l name=spatie -d --rm spatie/docker', $command);
    }


}
