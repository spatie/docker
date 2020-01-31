<?php

namespace Spatie\Docker;

class DockerContainer
{
    public array $authorizedKeys = [];

    public ?string $name = null;

    public ?string $image = null;

    public int $port = 4848;

    public static function new(): DockerContainer
    {
        return new self();
    }

    public function withAuthorizedKey(string $key): DockerContainer
    {
        $config = clone $this;

        $config->authorizedKeys[] = $key;

        return $config;
    }

    public function named(string $name): DockerContainer
    {
        $config = clone $this;

        $config->name = $name;

        return $config;
    }

    public function image(string $image): DockerContainer
    {
        $config = clone $this;

        $config->image = $image;

        return $config;
    }

    public function port(int $port): DockerContainer
    {
        $config = clone $this;

        $config->port = $port;

        return $config;
    }
}
