<?php

declare(strict_types=1);

use Spatie\Docker\EnvironmentMapping;

it('should convert to a string correctly', function () {
    $mapping = new EnvironmentMapping('APP_URL', 'http://localhost');

    expect($mapping)->toEqual('-e APP_URL=http://localhost');
});
