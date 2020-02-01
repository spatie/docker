<?php

namespace Spatie\Docker;

class DockerContainer
{
    public array $authorizedKeys = [];

    public string $name = '';

    public string $image = '';

    public int $port = 4848;

    public static function new(): DockerContainer
    {
        return new self();
    }

    public function installPublicKey(string $key): DockerContainer
    {
        $this->authorizedKeys[] = $key;

        return $this;
    }

    public function named(string $name): DockerContainer
    {
        $this->name = $name;

        return $this;
    }

    public function image(string $image): DockerContainer
    {
        $this->image = $image;

        return $this;
    }

    public function port(int $port): DockerContainer
    {
        $this->port = $port;

        return $this;
    }
}
