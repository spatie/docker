<?php

declare(strict_types=1);

use Spatie\Docker\PortMapping;

it('should convert to a string correctly with tcp protocol as default', function () {
    $portMapping = new PortMapping(8080, 80);

    expect($portMapping)->toEqual('-p 8080:80/tcp');
});

it('should convert to a string correctly with configured udp protocol', function () {
    $portMapping = new PortMapping(8080, 80, 'udp');

    expect($portMapping)->toEqual('-p 8080:80/udp');
});
