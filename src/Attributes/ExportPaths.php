<?php

namespace Antlur\Export\Attributes;

use Antlur\Export\Contracts\PathProvider;
use Attribute;

#[Attribute]
class ExportPaths
{
    public function __construct(private array|string $exportPaths)
    {
    }

    public function paths(): array
    {
        if (is_array($this->exportPaths)) {
            return $this->exportPaths;
        }

        $exception = new \Exception('ExportPaths must be an array or an instance of '.PathProvider::class);

        try {
            $exportPaths = app($this->exportPaths);
        } catch (\Exception $e) {
            throw $exception;
        }

        if (! $exportPaths instanceof PathProvider) {
            throw $exception;
        }

        return $exportPaths->paths();
    }
}
