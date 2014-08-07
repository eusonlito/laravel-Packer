# Laravel Packer

[![Build Status](https://travis-ci.org/eusonlito/laravel-Packer.svg?branch=master)](https://travis-ci.org/eusonlito/laravel-Packer)
[![Latest Stable Version](https://poser.pugx.org/laravel/packer/v/stable.png)](https://packagist.org/packages/laravel/packer)
[![Total Downloads](https://poser.pugx.org/laravel/packer/downloads.png)](https://packagist.org/packages/laravel/packer)
[![License](https://poser.pugx.org/laravel/packer/license.png)](https://packagist.org/packages/laravel/packer)

Inspired by: https://github.com/ceesvanegmond/minify

With this package you can pack and minify your existing css and javascript files. This process can be a little tough, this package simplies this process and automates it.

Also, you can resize/crop images to adapt thumbnails into your layouts.

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

        // Pack a simple file using base_folder option as storage folder to packed file
        {{ Packer::css('/css/main.css', 'css/main.css') }}

        // Packing multiple files
        {{ Packer::css(['/css/main.css', '/css/bootstrap.css'], '/storage/cache/css/styles.css') }}

        // Packing multiple files using base_folder option as storage folder to packed file
        {{ Packer::css(['/css/main.css', '/css/bootstrap.css'], 'css/styles.css') }}

        // Packing multiple files with autonaming based
        {{ Packer::css(['/css/main.css', '/css/bootstrap.css'], '/storage/cache/css/') }}

        // pack and combine all css files in given folder
        {{ Packer::cssDir('/css/', '/storage/cache/css/all.css') }}

        // pack and combine all css files in given folder using base_folder option as storage folder to packed file
        {{ Packer::cssDir('/css/', 'css/all.css') }}

        // Packing multiple folders
        {{ Packer::cssDir(['/css/', '/theme/'], '/storage/cache/css/all.css') }}

        // Packing multiple folders with recursive search
        {{ Packer::cssDir(['/css/', '/theme/'], '/storage/cache/css/all.css', true) }}

        // Packing multiple folders with recursive search and autonaming
        {{ Packer::cssDir(['/css/', '/theme/'], '/storage/cache/css/', true) }}

        // Packing multiple folders with recursive search and autonaming using base_folder option as storage folder to packed file
        {{ Packer::cssDir(['/css/', '/theme/'], 'css/', true) }}
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
        {{ Packer::js('/js/main.js', '/storage/cache/js/main.js') }}

        // Pack a simple file using base_folder option as storage folder to packed file
        {{ Packer::js('/js/main.js', 'js/main.js') }}

        // Packing multiple files
        {{ Packer::js(['/js/main.js', '/js/bootstrap.js'], '/storage/cache/js/styles.js') }}

        // Packing multiple files using base_folder option as storage folder to packed file
        {{ Packer::js(['/js/main.js', '/js/bootstrap.js'], 'js/styles.js') }}

        // Packing multiple files with autonaming based
        {{ Packer::js(['/js/main.js', '/js/bootstrap.js'], '/storage/cache/js/') }}

        // pack and combine all js files in given folder
        {{ Packer::jsDir('/js/', '/storage/cache/js/all.js') }}

        // pack and combine all js files in given folder using base_folder option as storage folder to packed file
        {{ Packer::jsDir('/js/', 'js/all.js') }}

        // Packing multiple folders
        {{ Packer::jsDir(['/js/', '/theme/'], '/storage/cache/js/all.js') }}

        // Packing multiple folders with recursive search
        {{ Packer::jsDir(['/js/', '/theme/'], '/storage/cache/js/all.js', true) }}

        // Packing multiple folders with recursive search and autonaming
        {{ Packer::jsDir(['/js/', '/theme/'], '/storage/cache/js/', true) }}

        // Packing multiple folders with recursive search and autonaming using base_folder option as storage folder to packed file
        {{ Packer::jsDir(['/js/', '/theme/'], 'js/', true) }}
    </body>
</html>
```

#### Images
All transform options availables at https://github.com/oscarotero/imageCow

```php
// app/views/hello.blade.php

<html>
    <body>
    ...
        // Set width size to 500px
        <img src="{{ Packer::img('/images/picture.jpg', '/storage/cache/images/', 'resize,500') }}" />

        // Crop image to 200px square
        <img src="{{ Packer::img('/images/picture.jpg', '/storage/cache/images/', 'resizeCrop,200,200') }}" />

        // Crop image to 200px square center middle using base_folder parameter
        <img src="{{ Packer::img('/images/picture.jpg', 'images/', 'resizeCrop,200,200') }}" />

        // Crop image to 200px square center top using base_folder parameter
        <img src="{{ Packer::img('/images/picture.jpg', 'images/', 'resizeCrop,200,200,center,top') }}" />
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
    | Base folder to store packed files
    |--------------------------------------------------------------------------
    |
    | If you are using relative paths to second paramenter in css and js
    | commands, this files will be created with this folder as base.
    |
    | This folder in relative to /public/
    |
    */

    'base_folder' => '/storage/cache/',

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

    'check_timestamps' => true
);

```
If you set the `'check_timestamps'` option, a timestamp value will be added to final filename.
