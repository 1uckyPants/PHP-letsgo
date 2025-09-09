<?php

declare(strict_types=1);

namespace Tests\DataProviders;

class RouterDataProvider
{
    public static function routeNotFoundCases(): array
    {
        return [
            ['/users', 'delete'],
            ['/users', 'index'],
            ['/users', 'get'],
            ['/users', 'post']
        ];
    }
}