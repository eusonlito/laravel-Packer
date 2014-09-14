<?php namespace Laravel\Packer;

use App, Config;
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
        $this->package('laravel/packer');
    }

    /**
	 * Register the service provider.
	 *
	 * @return void
	 */
    public function register()
    {
        $this->app->bindShared('packer', function () {
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
        Config::get('packer::config');

        $config = Config::getItems()['packer::config'];

        if (empty($config['environment'])) {
            $config['environment'] = App::environment();
        }

        $config['public_path'] = public_path();
        $config['asset'] = asset('');

        return $config;
    }
}
