# Configurable Composer Manager for Statamic

The Configurable Composer Manager for Statamic addon allows you to make configuration changes to the Composer executable that Statamic internally uses to manage addon updates (or to even use your own version of Composer).

If you can't get Statamic to refresh your addons when developing on a Windows system - you're in the right place.

## Installation

Copy the `ConfigurableComposerManager` folder into your `site/addons` folder. That should be all you have to do!

## Environment Variables

Composer allows you to set a number of environment variables to override certain settings. These can be viewed here: [https://getcomposer.org/doc/03-cli.md#environment-variables](https://getcomposer.org/doc/03-cli.md#environment-variables).

To set these environment variables using this addon, add settings similar to the following in a `site/settings/addons/configurable_composer_manager.yaml` configuration file:

```yaml
"composer": "composer.phar"
environment-variables:
  "COMPOSER_HOME": "@composer"
```

You can set to new environment variables by adding items to the `environment-variables` configuration entry. For example, the following would set the `COMPOSER_ALLOW_SUPERUSER` environment variable when invoking the Composer application:

```yaml
environment-variables:
    "COMPOSER_HOME": "@composer"
    "COMPOSER_ALLOW_SUPERUSER": "1"
```

## Values That Start With `@`

You will notice that the default `COMPOSER_HOME` value starts with the `@` symbol. This instructs the addon to return a path relative to your site's `local` directory. So the `@composer` value would be translated into `<your site>/local/composer/`.

## Changing the Composer Executable

On certain systems it might be useful to change the Composer executable that is used. This can be done by modifying the `composer` configuration entry:

```yaml
"composer": "composer.phar"
```

By default the `composer` entry is set to `composer.phar`, which will instruct the addon to use the Composer executable that ships with Statamic. Generally you shouldn't have to modify this, but if need to, simply set this value to the full path to the Composer executable you want to use.

A good example of when to change this is on a Windows system with a system-wide installation of Composer and the bundled Composer executable begins throwing network connection errors.

This configuration item becomes most useful when taking advantage of Statamic's environment setting interpolation: [https://docs.statamic.com/environments#environment-settings](https://docs.statamic.com/environments#environment-settings).