<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

Artisan::command('app:heartbeat', function (): void {
  $this->comment('SIMPEG-ASSET is alive.');
})->purpose('Display an application heartbeat message');
