<?php
namespace Laravel\Packer;

use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class PackerServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('laravel/packer');

        AliasLoader::getInstance()->alias('Packer', 'Laravel\Packer\Facades\Packer');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['Packer'] = $this->app->share(function($app) {
            return new Packer(
                [
                    'css_build_path' => Config::get('packer::css_build_path'),
                    'js_build_path' => Config::get('packer::js_build_path'),
                    'ignore_environments' => Config::get('packer::ignore_environments')
                ], $app->environment()
            );
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('packer');
	}
}
