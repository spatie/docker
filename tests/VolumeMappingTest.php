<?php

declare(strict_types=1);

use Spatie\Docker\VolumeMapping;

it('should convert to a string correctly', function () {
    $mapping = new VolumeMapping('/foo', '/bar');

    expect($mapping)->toEqual('-v /foo:/bar');
});
