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
    public function it_will_deamonize_and_clean_up_the_container_by_default()
    {
        $command = $this->container->getStartCommand();

        $this->assertEquals('docker run -d --rm spatie/docker', $command);
    }

    /** @test */
    public function it_can_not_be_deamonized()
    {
        $command = $this->container
            ->doNotDeamonize()
            ->getStartCommand();

        $this->assertEquals('docker run --rm spatie/docker', $command);
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
            ->named('my-name')
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
}
