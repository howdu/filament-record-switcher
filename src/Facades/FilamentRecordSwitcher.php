<?php

namespace Howdu\FilamentRecordSwitcher\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Howdu\FilamentRecordSwitcher\FilamentRecordSwitcher
 */
class FilamentRecordSwitcher extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Howdu\FilamentRecordSwitcher\FilamentRecordSwitcher::class;
    }
}
