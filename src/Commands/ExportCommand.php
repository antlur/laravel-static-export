<?php

namespace Antlur\Export\Commands;

use Antlur\Export\Facades\Export;
use Illuminate\Console\Command;

class ExportCommand extends Command
{
    public $signature = 'export';

    public $description = 'Export application to a static site.';

    public function handle(): int
    {
        $this->comment('All done');

        Export::export();

        return self::SUCCESS;
    }
}
