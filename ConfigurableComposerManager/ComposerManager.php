<?php

namespace Statamic\Addons\ConfigurableComposerManager;

use Illuminate\Support\Collection;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Statamic\Extend\Management\ComposerManager as StatamicComposerManager;
use Statamic\Contracts\Extend\Management\ComposerManager as ManagerContract;

class ComposerManager extends StatamicComposerManager implements ManagerContract
{

    /**
     * The environment variables to be set when invoking Composer.
     *
     * @var Collection
     */
    protected $environmentVariables = [];

    /**
     * The Composer executable to run.
     *
     * @var string
     */
    protected $composer = 'composer.phar';

    /**
     * Sets the environment variables to be used when invoking Composer.
     *
     * @param $vars
     */
    public function setEnvironmentVariables($vars)
    {
        $this->environmentVariables = $vars;
    }

    /**
     * Set the Composer executable to use.
     *
     * @param $composer
     */
    public function setComposer($composer)
    {
        $this->composer = ProcessUtils::escapeArgument($composer);
    }

    /**
     * Run composer update
     *
     * @param array|null $packages Packages to specifically update
     * @return mixed
     */
    public function update($packages = null)
    {
        $packages = join(' ', $packages);

        // Use ProcessUtils to locate the PHP executable; this is helpful for Windows systems.
        $binary = ProcessUtils::escapeArgument((new PhpExecutableFinder)->find(false));

        $command = sprintf($binary.' '.$this->composer.' update %s --prefer-dist --no-dev --optimize-autoloader', $packages);

        // Create the process and use our configurable environment variables instead.
        $process = new Process($command, $this->path(), $this->environmentVariables->all());

        $process->setTimeout(null);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return true;
    }

}