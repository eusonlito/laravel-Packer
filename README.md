# Laravel Packer

Adapted version from: https://github.com/ceesvanegmond/minify

With this package you can pack and minify your existing css and javascript files. This process can be a little tough, this package simplies this process and automates it.

## Installation

Begin by installing this package through Composer.

```js
{
    "require": {
        "laravel/packer": "master-dev"
    }
}
```

### Laravel installation

```php

// app/config/app.php

'providers' => [
    '...',
    'Laravel\Packer\PackerServiceProvider',
];
```

Publish the config file:
```
php artisan config:publish laravel/packer
```

When you've added the ```PackerServiceProvider``` an extra ```Packer``` facade is available.
You can use this Facade anywhere in your application

#### CSS
```php
// app/views/hello.blade.php

<html>
    <head>
        // Pack a simple file
        {{ Packer::css('/css/main.css', '/storage/cache/css/main.css') }}

        // Packing multiple files
        {{ Packer::css(['/css/main.css', '/css/bootstrap.css'], '/storage/cache/css/styles.css') }}
        
        // pack and combine all css files in given folder
        {{ Packer::cssDir('/css/', '/storage/cache/css/all.css') }}

        // Packing multiple folders
        {{ Packer::cssDir(['/css/', '/theme/'], '/storage/cache/css/all.css') }}

        // Packing multiple folders with recursive search
        {{ Packer::cssDir(['/css/', '/theme/'], '/storage/cache/css/all.css', true) }}
    </head>
</html>

```

CSS `url()` values will be converted to absolute path to avoid file references problems.

#### Javascript
```php
// app/views/hello.blade.php

<html>
    <body>
    ...
        // Pack a simple file
        {{ Packer::js('/js/main.js', '/storage/cache/js/scripts.js') }}

        // Packing multiple files
        {{ Packer::js(['/js/main.js', '/js/bootstrap.js'], '/storage/cache/js/scripts.js') }}
        
        // pack and combine all js files in given folder
        {{ Packer::jsDir('/js/', '/storage/cache/js/all.js') }}

        // Packing multiple folders
        {{ Packer::jsDir(['/js/', '/theme/'], '/storage/cache/js/all.js') }}

        // Packing multiple folders with recursive search
        {{ Packer::jsDir(['/js/', '/theme/'], '/storage/cache/js/all.js', true) }}
    </body>
</html>

```

### Config
```php
return array(

    /*
    |--------------------------------------------------------------------------
    | App environments to not pack
    |--------------------------------------------------------------------------
    |
    | These environments will not be minified and all individual files are
    | returned
    |
    */

    'ignore_environments' => ['local'],

    /*
    |--------------------------------------------------------------------------
    | Check if some have a recent timestamp
    |--------------------------------------------------------------------------
    |
    | Compare current packed file with all files to pack. If exists one more
    | recent than packed file, will be packed again.
    |
    */

    'check_timestamps' => true
);

```
If you set the `'check_timestamps'` option, a timestamp value will be added to final filename.
