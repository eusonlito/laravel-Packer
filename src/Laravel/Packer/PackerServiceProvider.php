<?php
namespace Laravel\Packer;

use App, Config;
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
		$this->app['Packer'] = $this->app->share(function() {
            return new Packer($this->config());
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['packer'];
	}

    /**
     * Get the base settings from config file
     *
     * @return array
     */
    public function config()
    {
        $config = Config::get('packer::config');

        if (empty($config['environment'])) {
            $config['environment'] = App::environment();
        }

        return $config;
    }
}
