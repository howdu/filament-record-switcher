<?php

namespace Howdu\FilamentRecordSwitcher\Commands;

use Illuminate\Console\Command;

class FilamentRecordSwitcherCommand extends Command
{
    public $signature = 'filament-record-switcher';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
