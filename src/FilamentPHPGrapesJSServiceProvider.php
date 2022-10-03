<?php

namespace Calima\FilamentPHPGrapesJS;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentPHPGrapesJSServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filamentphp-grapesjs';

    protected array $resources = [
        // CustomResource::class,
    ];

    protected array $pages = [
        // CustomPage::class,
    ];

    protected array $widgets = [
        // CustomWidget::class,
    ];

    protected array $styles = [
        'plugin-filamentphp-grapesjs' => __DIR__ . '/../resources/dist/filamentphp-grapesjs.css',
    ];

    protected array $scripts = [
        'plugin-filamentphp-grapesjs' => __DIR__ . '/../resources/dist/filamentphp-grapesjs.js',
    ];

    // protected array $beforeCoreScripts = [
    //     'plugin-filamentphp-grapesjs' => __DIR__ . '/../resources/dist/filamentphp-grapesjs.js',
    // ];

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }
}
