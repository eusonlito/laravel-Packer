<?php
namespace Eusonlito\LaravelPacker;

use Illuminate\Support\ServiceProvider;

class PackerServiceProvider extends ServiceProvider
{
    /**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('packer.php')
        ]);
    }

    /**
	 * Register the service provider.
	 *
	 * @return void
	 */
    public function register()
    {
        $this->app['packer'] = $this->app->share(function($app) {
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
        $config = config('packer');

        if (empty($config['environment'])) {
            $config['environment'] = app()->environment();
        }

        $config['public_path'] = public_path();
        $config['asset'] = asset('');

        return $config;
    }
}
