# Laravel >= 5 Packer

[![Build Status](https://travis-ci.org/eusonlito/laravel-Packer.svg?branch=master)](https://travis-ci.org/eusonlito/laravel-Packer)
[![Latest Stable Version](https://poser.pugx.org/laravel/packer/v/stable.png)](https://packagist.org/packages/laravel/packer)
[![Total Downloads](https://poser.pugx.org/laravel/packer/downloads.png)](https://packagist.org/packages/laravel/packer)
[![License](https://poser.pugx.org/laravel/packer/license.png)](https://packagist.org/packages/laravel/packer)

Inspired by: https://github.com/ceesvanegmond/minify

With this package you can pack and minify your existing css and javascript files. This process can be a little tough, this package simplies this process and automates it.

Also, you can resize/crop images to adapt thumbnails into your layouts.

If you want a Laravel <= 4.2 compatible version, please use `v4.2` branch.

## Installation

Begin by installing this package through Composer.

```js
{
    "require": {
        "eusonlito/laravel-packer": "master-dev"
    }
}
```

### Laravel installation

```php

// config/app.php

'providers' => [
    '...',
    'Eusonlito\LaravelPacker\PackerServiceProvider',
];

'aliases' => [
    '...',
    'Packer'    => 'Eusonlito\LaravelPacker\Facade',
];
```

Publish the config file:

```
php artisan vendor:publish
```

Now you have a ```Packer``` facade available.

#### CSS

```php
// resources/views/hello.blade.php

<html>
    <head>
        // Pack a simple file
        {!! Packer::css('/css/main.css', '/storage/cache/css/main.css') !!}

        // Pack a simple file using cache_folder option as storage folder to packed file
        {!! Packer::css('/css/main.css', 'css/main.css') !!}

        // Packing multiple files
        {!! Packer::css(['/css/main.css', '/css/bootstrap.css'], '/storage/cache/css/styles.css') !!}

        // Packing multiple files using cache_folder option as storage folder to packed file
        {!! Packer::css(['/css/main.css', '/css/bootstrap.css'], 'css/styles.css') !!}

        // Packing multiple files with autonaming based
        {!! Packer::css(['/css/main.css', '/css/bootstrap.css'], '/storage/cache/css/') !!}

        // pack and combine all css files in given folder
        {!! Packer::cssDir('/css/', '/storage/cache/css/all.css') !!}

        // pack and combine all css files in given folder using cache_folder option as storage folder to packed file
        {!! Packer::cssDir('/css/', 'css/all.css') !!}

        // Packing multiple folders
        {!! Packer::cssDir(['/css/', '/theme/'], '/storage/cache/css/all.css') !!}

        // Packing multiple folders with recursive search
        {!! Packer::cssDir(['/css/', '/theme/'], '/storage/cache/css/all.css', true) !!}

        // Packing multiple folders with recursive search and autonaming
        {!! Packer::cssDir(['/css/', '/theme/'], '/storage/cache/css/', true) !!}

        // Packing multiple folders with recursive search and autonaming using cache_folder option as storage folder to packed file
        {!! Packer::cssDir(['/css/', '/theme/'], 'css/', true) !!}
    </head>
</html>
```

CSS `url()` values will be converted to absolute path to avoid file references problems.

#### Javascript

```php
// resources/views/hello.blade.php

<html>
    <body>
    ...
        // Pack a simple file
        {!! Packer::js('/js/main.js', '/storage/cache/js/main.js') !!}

        // Pack a simple file using cache_folder option as storage folder to packed file
        {!! Packer::js('/js/main.js', 'js/main.js') !!}

        // Packing multiple files
        {!! Packer::js(['/js/main.js', '/js/bootstrap.js'], '/storage/cache/js/styles.js') !!}

        // Packing multiple files using cache_folder option as storage folder to packed file
        {!! Packer::js(['/js/main.js', '/js/bootstrap.js'], 'js/styles.js') !!}

        // Packing multiple files with autonaming based
        {!! Packer::js(['/js/main.js', '/js/bootstrap.js'], '/storage/cache/js/') !!}

        // pack and combine all js files in given folder
        {!! Packer::jsDir('/js/', '/storage/cache/js/all.js') !!}

        // pack and combine all js files in given folder using cache_folder option as storage folder to packed file
        {!! Packer::jsDir('/js/', 'js/all.js') !!}

        // Packing multiple folders
        {!! Packer::jsDir(['/js/', '/theme/'], '/storage/cache/js/all.js') !!}

        // Packing multiple folders with recursive search
        {!! Packer::jsDir(['/js/', '/theme/'], '/storage/cache/js/all.js', true) !!}

        // Packing multiple folders with recursive search and autonaming
        {!! Packer::jsDir(['/js/', '/theme/'], '/storage/cache/js/', true) !!}

        // Packing multiple folders with recursive search and autonaming using cache_folder option as storage folder to packed file
        {!! Packer::jsDir(['/js/', '/theme/'], 'js/', true) !!}
    </body>
</html>
```

#### Images
All transform options availables at https://github.com/oscarotero/imageCow

```php
// resources/views/hello.blade.php

<html>
    <body>
    ...
        // Set width size to 500px using cache_folder default parameter (from settings)
        <img src="{{ Packer::img('/images/picture.jpg', 'resize,500') }}" />

        // Crop image to 200px square with custom cache folder (full path)
        <img src="{{ Packer::img('/images/picture.jpg', 'resizeCrop,200,200', '/storage/cache/images/') }}" />

        // Crop image to 200px square center middle with custom cache folder (using cache_folder as base)
        <img src="{{ Packer::img('/images/picture.jpg', 'resizeCrop,200,200', 'images/') }}" />

        // Crop image to 200px square center top with custom cache folder (using cache_folder as base)
        <img src="{{ Packer::img('/images/picture.jpg', 'resizeCrop,200,200,center,top', 'images/') }}" />
    </body>
</html>

```

### Config

```php
return array(

    /*
    |--------------------------------------------------------------------------
    | Current environment
    |--------------------------------------------------------------------------
    |
    | Set the current server environment. Leave empty to laravel autodetect
    |
    */

    'environment' => '',

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
    | Public accessible path
    |--------------------------------------------------------------------------
    |
    | Set absolute folder path to public view from web. If you are using
    | laravel, this value will be set with public_path() function
    |
    */

    'public_path' => realpath(getenv('DOCUMENT_ROOT')),

    /*
    |--------------------------------------------------------------------------
    | Asset absolute location
    |--------------------------------------------------------------------------
    |
    | Set absolute URL location to asset folder. Many times will be same as
    | public_path but using absolute URL. If you are using laravel, this value
    | will be set with asset() function
    |
    */

    'asset' => 'http://'.getenv('SERVER_NAME').'/',

    /*
    |--------------------------------------------------------------------------
    | Base folder to store packed files
    |--------------------------------------------------------------------------
    |
    | If you are using relative paths to second paramenter in css and js
    | commands, this files will be created with this folder as base.
    |
    | This folder in relative to 'public_path' value
    |
    */

    'cache_folder' => '/storage/cache/',

    /*
    |--------------------------------------------------------------------------
    | Check if some file to pack have a recent timestamp
    |--------------------------------------------------------------------------
    |
    | Compare current packed file with all files to pack. If exists one more
    | recent than packed file, will be packed again with a new autogenerated
    | name.
    |
    */

    'check_timestamps' => true,

    /*
    |--------------------------------------------------------------------------
    | Check if you want minify css files or only pack them together
    |--------------------------------------------------------------------------
    |
    | You can check this option if you want to join and minify all css files or
    | only join files
    |
    */

    'css_minify' => true,

    /*
    |--------------------------------------------------------------------------
    | Check if you want minify js files or only pack them together
    |--------------------------------------------------------------------------
    |
    | You can check this option if you want to join and minify all js files or
    | only join files
    |
    */

    'js_minify' => true,

    /*
    |--------------------------------------------------------------------------
    | Use fake images stored in src/images/ when original image does not exists
    |--------------------------------------------------------------------------
    |
    | You can use fake images in your developments to avoid not existing
    | original images problems. Fake images are stored in src/images/ and used
    | with a rand
    |
    */

    'images_fake' => true
);
```

If you set the `'check_timestamps'` option, a timestamp value will be added to final filename.

### Using Packer outside Laravel

```php
require (__DIR__.'/vendor/autoload.php');

// Check default settings
$config = require (__DIR__.'/src/config/config.php');

$Packer = new Eusonlito\LaravelPacker\Packer($config);

echo $Packer->css([
    '/resources/css/styles-1.css',
    '/resources/css/styles-2.css'
], 'css/styles.css')->render();

echo $Packer->js('/resources/js/scripts.js', 'js/scripts.js')->render();

echo $Packer->js([
    '/resources/js/scripts-1.js',
    '/resources/js/scripts-2.js'
], 'js/')->render();
```
