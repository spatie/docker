<?php

namespace Spatie\Docker\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Docker\Docker;
use Spatie\Docker\DockerContainer;
use Spatie\Docker\DockerContainerInstance;

class ExampleTest extends TestCase
{
    /** @test */
    public function true_is_true()
    {
        DockerContainer::new()
            ->named('Spatie')
            ->stopAfterCompletion()
            ->port(4848)
            ->image('spatie/dock')
            ->start()
            ->addPublicKey(file_get_contents('/Users/freek/.ssh/id_rsa.pub'))
            ->addFiles(__DIR__  . '/../src', '/root');
    }
}
