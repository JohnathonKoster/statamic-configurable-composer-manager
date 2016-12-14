<?php

namespace Statamic\Addons\ConfigurableComposerManager;

use Illuminate\Support\Str;
use Statamic\Extend\Extensible;
use Statamic\Extend\ServiceProvider;
use Statamic\Contracts\Extend\Management\ComposerManager as ComposerManagerContract;

class ConfigurableComposerManagerServiceProvider extends ServiceProvider
{
    use Extensible;

    protected $addon_name = "ConfigurableComposerManager";

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    public $defer = true;


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $vars = collect($this->getConfig('environment-variables', []))->map(function($value) {
            if (Str::startsWith($value, '@')) {
                return local_path(mb_substr($value, 1));
            }

            return $value;
        });

        $this->app->bind(ComposerManagerContract::class, function() use ($vars) {
            $manager = new ComposerManager;
            $manager->setEnvironmentVariables($vars);
            $manager->setComposer($this->getConfig('composer', 'composer.phar'));
            return $manager;
        });
    }

}
