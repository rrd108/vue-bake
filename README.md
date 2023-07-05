# VueBake plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require rrd108/vue-bake
```

## Load the plugin

In your `src/Application.php` file, add the following code:

```php
protected function bootstrapCli(): void
{
  // ..
  // Load more plugins here
  $this->addPlugin('VueBake');
}
```

## Bake VueJs components

```
bin/cake bake vue_component `ModelName` `-l ts` `-p ../frontentd/src/components`
```

Where

- `ModelName` is the name of the model to bake components for, for example `Posts`
- `-l` or `--lang` is the language. Defaults to `js`. Available languages are `js` and `ts`
- `-p` or `--path` `../frontentd/src/components` path to where the output file should saved. Relative to `src`.
