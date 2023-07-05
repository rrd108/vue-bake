# VueBake plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require rrd108/vue-bake
```

## Bake VueJs components

```
bin/cake bake vue_component `ModelName` `ts` `../frontentd/src/components`
```

Where

-   `ModelName` is the name of the model to bake components for
-   `ts` is the language. Defaults to `js`. Available languages are `js` and `ts`
-   `../frontentd/src/components` path to where the output file should saved. Relative to `src`.
