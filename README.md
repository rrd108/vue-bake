# VueBake plugin for CakePHP

Bake VueJs components from CakePHP 4 models.

For CakePHP 5 version see rrd108/vue-bake

Are you a full stack developer working with the powerful combination of **Vue SPA-s** and **CakePHP** as a REST API backend? If so, we have something special in store for you.

We understand the value of **efficient development processes** and the convenience that **code generation** brings. As avid fans of CakePHP's **bake automatic code generation** capabilities, we noticed a missing piece when it came to **template generation**. That's why we took it upon ourselves to bridge this gap and create the **CakePHP VueBake Plugin**.

Our plugin is designed to enhance your development workflow by seamlessly **generating VueJs components** directly from your CakePHP models. Say goodbye to the tedious task of manually creating Vue components for each model – our plugin automates the process, **saving you valuable time and effort**.

By leveraging the power of CakePHP's backend as a REST API and combining it with the versatility and interactivity of Vue SPA-s, you'll experience a **harmonious synergy** that brings your full stack projects to new heights.

**Key Features** of the CakePHP VueBake Plugin:

1. **Automatic VueJs Component Generation**: Our plugin effortlessly converts your CakePHP models into VueJs components, complete with the necessary code and structure. No more repetitive manual coding – let the plugin do the heavy lifting for you.

2. **Seamless Integration**: The plugin seamlessly integrates into your existing CakePHP and Vue SPA project, preserving the integrity of your codebase while enhancing your development capabilities.

3. **Customization Options**: We understand that each project is unique, and that's why our plugin offers various customization options. Tailor the generated Vue components to match your specific requirements, ensuring a perfect fit for your application.

4. **JavaScript and TypeScript Support**: Our plugin supports both JavaScript and TypeScript, allowing you to choose the language that best suits your project. The plugin generates Typescript interfaces for your models, making it easy to work with TypeScript.

5. **Time and Effort Savings**: With the CakePHP VueBake Plugin, you can significantly reduce development time and effort. Spend less time on boilerplate code and more time on building innovative features and delivering value to your users.

As full stack developers ourselves, we created this plugin with a deep understanding of the challenges and demands of working with Vue SPA-s and CakePHP. Our goal is to empower you with a **seamless development experience**, allowing you to focus on what truly matters – creating exceptional applications.

So why wait? Try out the **CakePHP VueBake Plugin** today and unlock the full potential of your Vue SPA and CakePHP stack. **Elevate your development workflow** and experience the convenience of automatic VueJs component generation like never before.

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require rrd108/vue-bake:^0.1
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
bin/cake bake vue_component ModelName -l ts -p ../../frontend/src/components
```

- `ModelName`: Specify the name of the model for which you want to generate components. For example, if you want to bake components for the `Posts` model, replace `ModelName` with `Posts`.

- `-l` or `--lang`: (Optional) Specify the language for the generated components. By default, it is set to `js` (JavaScript). However, you can choose `ts` for TypeScript. For instance, use `-l ts` to generate `components` and `interfaces` using TypeScript.

- `-p` or `--path`: (Optional) Provide the path where the output file should be saved. The path should be relative to the `src` directory. Default is `src/VueComponents`. For example, `-p ../../frontend/src/components` specifies that the generated components should be saved in the `frontend/src/components` directory 2 levels above the CakePHP's `src` directory - so it is out of the CakePHP's directory structure.

_Note:_ Make sure to replace the placeholders (`ModelName`, `../frontend/src/components`) with the actual values relevant to your project.
