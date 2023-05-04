<?php

use Spatie\Docker\DockerContainer;
use Spatie\Docker\DockerContainerInstance;

beforeEach(function () {
    $this->containerInstance = new DockerContainerInstance(new DockerContainer('spatie/docker'), '1234', 'test');
});

it('defaults process timeout to 60s', function () {
    $process = $this->containerInstance->execute('whoami');

    expect($process->getTimeout())->toEqual(60);
});

it('can set a custom process timeout', function () {
    $process = $this->containerInstance->execute('whoami', 3600);

    expect($process->getTimeout())->toEqual(3600);
});
