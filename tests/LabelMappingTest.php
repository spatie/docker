<?php

declare(strict_types=1);

namespace Spatie\Docker\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Docker\LabelMapping;

class LabelMappingTest extends TestCase
{
    /** @test */
    public function it_should_convert_to_a_string_correctly()
    {
        $mapping = new LabelMapping('type', 'primary');

        $this->assertEquals('-l type=primary', $mapping);
    }
}
