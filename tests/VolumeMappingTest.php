<?php

declare(strict_types=1);

namespace Spatie\Docker\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Docker\VolumeMapping;

class VolumeMappingTest extends TestCase
{
    /** @test */
    public function it_should_convert_to_a_string_correctly()
    {
        $mapping = new VolumeMapping('/foo', '/bar');

        $this->assertEquals('-v /foo:/bar', $mapping);
    }
}
