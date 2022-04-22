<?php

declare(strict_types=1);

namespace Spatie\Docker\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Docker\EnvironmentMapping;

class EnvironmentMappingTest extends TestCase
{
    /** @test */
    public function it_should_convert_to_a_string_correctly()
    {
        $mapping = new EnvironmentMapping('APP_URL', 'http://localhost');

        $this->assertEquals('-e APP_URL=http://localhost', $mapping);
    }
}
