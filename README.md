# Manage docker containers with PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/docker.svg?style=flat-square)](https://packagist.org/packages/spatie/docker)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/docker/run-tests?label=tests)](https://github.com/spatie/docker/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/docker.svg?style=flat-square)](https://packagist.org/packages/spatie/docker)

This package provides a nice way to start docker containers and execute commands on them.

````php
$containerInstance = DockerContainer::create($imageName)->start();

$process = $containerInstance->execute('whoami');

$process->getOutput(); // returns the name of the user inside the docker container
````

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/docker.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/docker)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/docker
```

## Usage

You can get an instance of a docker container using

```php
$containerInstance = DockerContainer::create($imageName)->start();
```

By default the container will be daemonized and it will be cleaned up after it exists.

### Customizing the docker container

#### Prevent daemonization

If you don't want your docker being daemonized, call `doNotDaemonize`.

```php
$containerInstance = DockerContainer::create($imageName)
    ->doNotDaemonize()
    ->start();
```

#### Prevent automatic clean up

If you don't want your docker being cleaned up after it exists, call `doNotCleanUpAfterExit`.

```php
$containerInstance = DockerContainer::create($imageName)
    ->doNotCleanUpAfterExit()
    ->start();
```

#### Priviliged

If you want your docker being privileged, call `privileged`.

```php
$containerInstance = DockerContainer::create($imageName)
    ->privileged()
    ->start();
```

#### Naming the container

You can name the container by passing the name as the second argument to the constructor.

```php
new DockerContainer($imageName, $nameOfContainer));
```

Alternatively, use the `name` method.

```php
$containerInstance = DockerContainer::create($imageName)
    ->name($yourName)
    ->start();
```

#### Mapping ports

You can map ports between the host machine and the docker container using the `mapPort` method. To map multiple ports, just call `mapPort` multiple times.

```php
$containerInstance = DockerContainer::create($imageName)
    ->mapPort($portOnHost, $portOnContainer)
    ->mapPort($anotherPortOnHost, $anotherPortOnContainer)
    ->start();
```

#### Environment variables

You can set environment variables using the `setEnvironmentVariable` method. To add multiple arguments, just call `setEnvironmentVariable` multiple times.

```php
$containerInstance = DockerContainer::create($imageName)
    ->setEnvironmentVariable($variableKey, $variableValue)
    ->setEnvironmentVariable($anotherVariableKey, $anotherVariableValue)
    ->start();
```

#### Setting Volumes

You can set volumes using the `setVolume` method. To add multiple arguments, just call `setVolume` multiple times.

```php
$containerInstance = DockerContainer::create($imageName)
    ->setVolume($pathOnHost, $pathOnDocker)
    ->setVolume($anotherPathOnHost, $anotherPathOnDocker)
    ->start();
```

#### Setting Labels

You can set labels using the `setLabel` method. To add multiple arguments, just call `setLabel` multiple times.

```php
$containerInstance = DockerContainer::create($imageName)
    ->setLabel($labelName, $labelValue)
    ->setLabel($anotherLabelName, $anotherLabelValue)
    ->start();
```

#### Automatically stopping the container after PHP exists

When using this package in a testing environment, it can be handy that the docker container is stopped after `__destruct` is called on it (mostly this will happen when the PHP script ends). You can enable this behaviour with the `stopOnDestruct` method.

```php
$containerInstance = DockerContainer::create($imageName)
    ->stopOnDestruct()
    ->start();
```

#### Specify a remote docker host for execution

You can set the host used for executing the container. The `docker` commandline accepts a daemon socket string. To connect to a remote docker host via ssh, use the syntax `ssh://username@hostname`. Note that the proper SSH keys will already need to be configured for this work.

```php
$containerInstance = DockerContainer::create($imageName)
    ->remoteHost('ssh://username@hostname')
    ->start();
```

#### Specify an alternative command to execute

Upon startup of a container, docker will execute the command defined within the container. The `command` method gives the ability to override to default command to run within the container.

```php
$containerInstance = DockerContainer::create($imageName)
    ->command('ls -l /etc')
    ->start();
```

#### Getting the start command string

You can get the string that will be executed when a container is started with the `getStartCommand` function

```php
// returns "docker run -d --rm spatie/docker"
DockerContainer::create($imageName)->getStartCommand();
```

### Available methods on the docker container instance

#### Executing a command

To execute a command on the container, use the `execute` method.

```php
$process = $instance->execute($command);
```

You can execute multiple command in one go by passing an array.

```php
$process = $instance->execute([$command, $anotherCommand]);
```

The execute method returns an instance of [`Symfony/Process`](https://symfony.com/doc/current/components/process.html).

You can check if your command ran successfully using the `isSuccessful` $method

```php
$process->isSuccessful(); // returns a boolean
```

You can get to the output using `getOutput()`. If the command did not run successfully, you can use `getErrorOutput()`. For more information on how to work with a `Process` head over to [the Symfony docs](https://symfony.com/doc/current/components/process.html).

#### Installing a public key

If you cant to connect to your container instance via SSH, you probably want to add a public key to it.

This can be done using the `addPublicKey` method.

```php
$instance->addPublicKey($pathToPublicKey);
```

It is assumed that the `authorized_keys` file is located in at `/root/.ssh/authorized_keys`. If this is not the case, you can specify the path of that file as a second parameter.

```php
$instance->addPublicKey($pathToPublicKey, $pathToAuthorizedKeys);
```

Note that in order to be able to connect via SSH, you should set up a SSH server in your `dockerfile`. Take a look at the `dockerfile` in the tests of this package for an example.

#### Adding files to your instance

Files can be added to an instance with `addFiles`.

```php
$instance->addFiles($fileOrDirectoryOnHost, $pathInContainer);
```

#### Adding other functions on the docker instance

The `Spatie\Docker\ContainerInstance` class is [macroable](https://github.com/spatie/macroable). This means you can add extra functions to it.

````php
Spatie\Docker\DockerContainerInstance::macro('whoAmI', function () {
    $process = $containerInstance->run('whoami');


    return $process->getOutput();
});

$containerInstance = DockerContainer::create($imageName)->start();

$containerInstace->whoAmI(); // returns of name of user in the docker container
````

### Testing

Before running the tests for the first time, you must build the `spatie/docker` container with:

````bash
composer build-docker
````

Next, you can run the tests with:
``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Ruben Van Assche](https://github.com/rubenvanassche)
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
