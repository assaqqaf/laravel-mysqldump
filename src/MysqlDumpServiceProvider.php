<?php namespace Assaqqaf\Mysqldump;

use Illuminate\Support\ServiceProvider;

class MysqlDumpServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->singleton('command.assaqqaf.dump', function ($app) {
            return $app['Assaqqaf\Mysqldump\Commands\MysqlDumpCommand'];
        });

        $this->commands('command.assaqqaf.dump');
	}

    /**
     * Register the package's custom Artisan commands.
     *
     * @param  array  $commands
     * @return void
     */
    public function commands($commands)
    {
        $commands = is_array($commands) ? $commands : func_get_args();

        // To register the commands with Artisan, we will grab each of the arguments
        // passed into the method and listen for Artisan "start" event which will
        // give us the Artisan console instance which we will give commands to.
        $events = $this->app['events'];

        $events->listen('artisan.start', function($artisan) use ($commands)
        {
            $artisan->resolveCommands($commands);
        });
    }

}
