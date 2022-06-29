<?php

namespace Spatie\Docker\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Docker\DockerContainer;
use Spatie\Docker\DockerNetwork;

class DockerNetworkTest extends TestCase
{
    private DockerNetwork $network;

    public function setUp(): void
    {
        parent::setUp();

        $this->network = new DockerNetwork('spatie-docker');
    }

    /** @test */
    public function it_can_be_created()
    {
        $command = $this->network
            ->getCreateCommand();

        $this->assertEquals('docker network create --driver bridge spatie-docker', $command);
    }

    /** @test **/
    public function it_can_change_the_name()
    {
        $command = $this->network
            ->name('my-name')
            ->getCreateCommand();

        $this->assertEquals('docker network create --driver bridge my-name', $command);
    }

    /** @test **/
    public function it_can_change_the_driver()
    {
        $command = $this->network
            ->driver('macvlan')
            ->getCreateCommand();

        $this->assertEquals('docker network create --driver macvlan spatie-docker', $command);
    }

    /** @test */
    public function it_can_set_optional_args()
    {
        $command = $this->network
            ->setOptionalArgs('--ipv6', '--internal')
            ->getCreateCommand();

        $this->assertEquals('docker network create --ipv6 --internal --driver bridge spatie-docker', $command);
    }

    /** @test */
    public function it_can_use_remote_docker_host()
    {
        $command = $this->network
            ->remoteHost('ssh://username@host')
            ->getCreateCommand();

        $this->assertEquals('docker -H ssh://username@host network create --driver bridge spatie-docker', $command);
    }

    /** @test */
    public function it_can_generate_remove_command()
    {
        $command = $this->network
            ->getRemoveCommand();

        $this->assertEquals('docker network rm spatie-docker', $command);
    }

    /** @test */
    public function it_can_generate_remove_command_with_remote_host()
    {
        $command = $this->network
            ->remoteHost('ssh://username@host')
            ->getRemoveCommand();

        $this->assertEquals('docker -H ssh://username@host network rm spatie-docker', $command);
    }

    /** @test */
    public function it_can_generate_exists_command()
    {
        $command = $this->network
            ->getExistsCommand();

        $this->assertEquals('docker network inspect spatie-docker', $command);
    }

    /** @test */
    public function it_can_generate_exists_command_with_remote_host()
    {
        $command = $this->network
            ->remoteHost('ssh://username@host')
            ->getExistsCommand();

        $this->assertEquals('docker -H ssh://username@host network inspect spatie-docker', $command);
    }
}
