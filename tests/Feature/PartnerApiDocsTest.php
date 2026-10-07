<?php

use Symfony\Component\Yaml\Yaml;

test('the partner api documentation page loads the openapi spec', function () {
    $this->get(route('api-docs'))
        ->assertSuccessful()
        ->assertSee(route('api-docs.spec'), false);

    $this->get(route('api-docs.spec'))
        ->assertSuccessful()
        ->assertHeader('Content-Type', 'application/yaml');
});

test('the openapi spec url has no file extension so hosts that block yaml files still serve it', function () {
    expect(route('api-docs.spec', absolute: false))->toBe('/docs/api/openapi');
});

test('the openapi spec documents every partner api route', function () {
    $spec = Yaml::parseFile(resource_path('api/openapi.yaml'));

    $documented = collect($spec['paths'])
        ->flatMap(fn (array $operations, string $path) => collect(array_keys($operations))
            ->map(fn (string $method): string => strtoupper($method).' /api/v1'.$path));

    $routes = collect(app('router')->getRoutes()->getRoutes())
        ->filter(fn ($route): bool => str_starts_with($route->uri(), 'api/v1/'))
        ->map(fn ($route): string => collect($route->methods())->reject(fn (string $method): bool => $method === 'HEAD')->first()
            .' /'.preg_replace('/\{(package|service|hotel)\}/', '{slug}', $route->uri()));

    expect($routes->sort()->values()->all())->toBe($documented->sort()->values()->all());
});
