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
    | Check if some have a recent timestamp
    |--------------------------------------------------------------------------
    |
    | Compare current packed file with all files to pack. If exists one more
    | recent than packed file, will be packed again.
    |
    */

    'check_timestamps' => true
);
