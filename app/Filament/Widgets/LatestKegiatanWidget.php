<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class LatestKegiatanWidget extends Widget
{
    protected string $view = 'filament.widgets.latest-kegiatan-widget';

    protected int | string | array $columnSpan = 'full';
}