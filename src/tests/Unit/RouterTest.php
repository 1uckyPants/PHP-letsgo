<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\RouteNotFoundException;
use App\Router;
use PHPUnit\Framework\TestCase;
use Tests\DataProviders\RouterDataProvider;

class RouterTest extends TestCase
{
    private Router $router;

    public function setUp(): void
    {
        parent::setUp();

        $this->router = new Router();
    }

    public function test_that_it_registers_a_route(): void
    {
        $this->router->register('get', '/users', ['Users', 'index']);

        $expected = [
            'get' => [
                '/users' => ['Users', 'index']
            ]
        ];

        $this->assertEquals($expected, $this->router->routes());
    }

    public function test_it_registers_a_get_route(): void
    {
        $this->router->get('/users', ['Users', 'index']);

        $expected = [
            'get' => [
                '/users' => ['Users', 'index']
            ]
        ];

        $this->assertEquals($expected, $this->router->routes());
    }

    public function test_it_registers_a_post_route(): void
    {
        $this->router->post('/users', ['Users', 'store']);

        $expected = [
            'post' => [
                '/users' => ['Users', 'store']
            ]
        ];

        $this->assertEquals($expected, $this->router->routes());
    }

    public function test_there_are_no_routes_when_router_is_created(): void
    {
        $this->router = new Router();

        $this->assertEmpty($this->router->routes());
    }

    public function test_this_test(): void
    {
        $this->router->register('get', '/users', fn() => [1, 2, 3]);

        $expected = [
            'get' => [
                '/users' => fn() => [1, 2, 3]
            ]
        ];

        $this->assertSame($expected, $this->router->routes());
    }

    #[\PHPUnit\Framework\Attributes\DataProviderExternal(RouterDataProvider::class, 'routeNotFoundCases')]
    public function test_it_throws_route_not_found_exception(
        string $requestUri,
        string $requestMethod
    ): void
    {
        $users = new class() {
            public function delete(): bool
            {
                return true;
            }
        };

        $this->router->post('/users', [$users::class, 'store']);
        $this->router->get('/users', ['Users', 'index']);

        $this->expectException(RouteNotFoundException::class);
        $this->router->resolve($requestUri, $requestMethod);
    }

    public function test_it_resolve_route_from_a_closure(): void
    {
        $this->router->get('/users', fn() => ['1', 2, 3]);

        $this->assertSame(
        [1, 2, 3],
        $this->router->resolve('/users', 'get'));
    }
}


//#[\PHPUnit\Framework\Attributes\DataProvider('routeNotFoundCases')]