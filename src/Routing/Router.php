<?php

namespace Antlur\Export\Routing;

use Closure;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionAttribute;
use Antlur\Export\Attributes\ExportPaths;
use Illuminate\Routing\Route as RoutingRoute;

class Router
{
    public function registeredRoutes(): array
    {
        return Route::getRoutes()->getRoutes();
    }

    public function appRoutes(): array
    {
        return $this->removeNonAppRoutes($this->registeredRoutes());
    }

    public function exportPaths(): array
    {
        $paths = [];

        foreach ($this->appRoutes() as $route) {
            $routeAction = $route->getAction();

            if (is_callable($routeAction['uses'])) {
                $paths[] = $route->uri();
                continue;
            }

            $controller = $route->getController();
            $method = $route->getActionMethod();

            $reflector = new ReflectionMethod($controller, $method);

            /** @var ReflectionAttribute|null */
            $attribute = collect($reflector->getAttributes())
                ->first(function (ReflectionAttribute $attribute) {
                    return $attribute->getName() === ExportPaths::class;
                });

            if (!$attribute) {
                $paths[] = $route->uri();
                continue;
            }

            /** @var ExportPaths */
            $exportPathMapAttribute = $attribute->newInstance();
            $exportPaths = $exportPathMapAttribute->paths();

            foreach ($exportPaths as $path) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * Filter out the fallback `{any}` and ignition routes
     * @return RoutingRoute[]
     */
    private function removeNonAppRoutes(array $routes): array
    {
        return array_filter($routes, function (RoutingRoute $r) {
            return !$this->isVendorRoute($r) && !$this->isFrameworkController($r);
        });
    }

    private function isVendorRoute(RoutingRoute $route): bool
    {
        $uses = $route->action['uses'];

        $path = '';

        if ($uses instanceof Closure) {
            $path = (new ReflectionFunction($uses))->getFileName();
        }

        if (is_string($uses)) {
            if (str_contains($uses, 'SerializableClosure')) {
                return false;
            }

            if ($this->isFrameworkController($route)) {
                return false;
            }

            $path = (new ReflectionClass($route->getControllerClass()))->getFileName();
        }

        return str_starts_with($path, base_path('vendor'));
    }

    private function isFrameworkController(RoutingRoute $route): bool
    {
        return in_array($route->getControllerClass(), [
            '\Illuminate\Routing\RedirectController',
            '\Illuminate\Routing\ViewController',
        ], true);
    }
}
