<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]

class Route
{
    public function __construct(
        public string $method = 'get',
        public string $uri = '',
        public array $middleware = [],
        public ?string $name = null
    ) {}
}
