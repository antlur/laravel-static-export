<?php

namespace Antlur\Export;

use Antlur\Export\Routing\Router;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class Export
{
    public function __construct(public Router $router)
    {
    }

    public function export()
    {
        File::ensureDirectoryExists(config('static-export.output_path'));

        $paths = $this->router->exportPaths();

        foreach ($paths as $path) {
            $html = $this->render($path);

            $this->save($path, $html);
        }
    }

    public function render(string $url): string
    {
        $request = Request::create($url);

        $kernel = app(config('static-export.kernal_namespace'));
        $response = $kernel->handle($request);

        return $response->getContent();
    }

    public function save(string $path, string $html)
    {
        $filename = ($path === '/')
            ? 'index.html'
            : $path.'/index.html';

        file_put_contents(config('static-export.output_path').'/'.$filename, $html);
    }
}
