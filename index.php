<?php

/**
 * @package     Akaunting
 * @copyright   2017-2023 Akaunting. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://akaunting.com
 */

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

define('LARAVEL_START', microtime(true));

$_ENV['ASSET_URL'] = $_ENV['ASSET_URL'] ?? './';
$_ENV['LIVEWIRE_ASSET_URL'] = $_ENV['LIVEWIRE_ASSET_URL'] ?? './';

if (file_exists($maintenance = __DIR__ . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the auto-loader
require __DIR__ . '/bootstrap/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->booted(function () {
    Livewire::setScriptRoute(function ($handle) {
        $base = request()->getBasePath();
        $base = str_replace('/public', '', $base);

        return Route::get($base . '/vendor/livewire/livewire/dist/livewire.min.js', $handle);
    });
});

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
