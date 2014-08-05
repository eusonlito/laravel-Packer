<?php

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
    | CSS build path
    |--------------------------------------------------------------------------
    |
    | Packer is an extension that can pack your css files into one build file.
    | The css_builds_path property is the location where the builded files are
    | stored. This is relative to your public path. Notice the trailing slash.
    | Note that this directory must be writeable.
    |
    */

    'css_build_path' => '/storage/cache/css/',

    /*
    |--------------------------------------------------------------------------
    | JS build path
    |--------------------------------------------------------------------------
    |
    | Packer is an extension that can packer your js files into one build file.
    | The js_build_path property is the location where the builded files are
    | stored. This is relative to your public path. Notice the trailing slash.
    | Note that this directory must be writeable.
    |
    */

    'js_build_path' => '/storage/cache/js/',

	/*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | You can set the base URL for the links generated with the configuration 
	| value. By default if empty HTTP_HOST would be used.
    |
    */
	'base_url' => ''
);