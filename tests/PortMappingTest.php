<?php

declare(strict_types=1);

use Spatie\Docker\PortMapping;

it('should convert to a string correctly', function () {
    $portMapping = new PortMapping(8080, 80);

    expect($portMapping)->toEqual('-p 8080:80');
});
