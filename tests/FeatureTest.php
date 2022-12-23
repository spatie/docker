<?php

use Spatie\Docker\DockerContainer;
use Spatie\Docker\DockerContainerInstance;
use Spatie\Docker\Exceptions\CouldNotStartDockerContainer;
use Spatie\Ssh\Ssh;

beforeEach(function () {
    $this->container = (new DockerContainer('spatie/docker'))
        ->name('spatie_docker_test')
        ->mapPort(4848, 22)
        ->stopOnDestruct();

    $this->ssh = (new Ssh('root', '0.0.0.0', 4848))
        ->usePrivateKey(__DIR__.'/keys/spatie_docker_package_id_rsa');
});

it('can start a container', function () {
    $container = $this->container->start();

    expect(strlen($container->getDockerIdentifier()))->toBeGreaterThan(0);
});

it('a public key can be added to a running container', function () {
    $container = (new DockerContainer('spatie/docker'))
        ->name('spatie_docker_test')
        ->mapPort(4848, 22)
        ->stopOnDestruct()
        ->start()
        ->addPublicKey(__DIR__.'/keys/spatie_docker_package_id_rsa.pub');

    $process = $this->ssh->execute('whoami');

    expect(trim($process->getOutput()))->toEqual('root');

    $container->stop();
});

it('files can be added to the container', function () {
    $container = $this->container->start()
        ->addPublicKey(__DIR__.'/keys/spatie_docker_package_id_rsa.pub')
        ->addFiles(__DIR__.'/stubs', '/test');

    $process = $this->ssh->execute([
        'cd /test',
        'find .',
    ]);

    $filesOnContainer = array_filter(explode(PHP_EOL, $process->getOutput()));

    expect($filesOnContainer)->toEqualCanonicalizing([
        '.',
        './subDirectory',
        './subDirectory/1.txt',
        './subDirectory/2.txt',
    ]);

    $container->stop();
});

it('will throw an exception if the container could not start', function () {
    $this->expectException(CouldNotStartDockerContainer::class);

    (new DockerContainer('non-existing-image'))->start();
});

it('the docker container is macroable', function () {
    DockerContainerInstance::macro('whoAmI', function () {
        /** @var \Symfony\Component\Process\Process $process */
        $process = $this->execute('whoami');

        return trim($process->getOutput());
    });

    $userName = (new DockerContainer('spatie/docker'))
        ->name('spatie_docker_test')
        ->mapPort(4848, 22)
        ->stopOnDestruct()
        ->start()
        ->whoAmI();

    expect($userName)->toEqual('root');
});

it('docker inspect information can be retrieved', function () {
    $container = (new DockerContainer('spatie/docker'))
        ->name('spatie_docker_test')
        ->stopOnDestruct()
        ->start();

    $info = $container->inspect();
    expect($info[0]['Id'])->toEqual($container->getDockerIdentifier());

    $container->stop();
});
