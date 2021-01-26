<?php


namespace App\Traits;

use Illuminate\Foundation\Application;

/**
 * @property Application $app
 */
trait Environment
{
    /**
     * Bootstrap
     */
    public function bootstrap()
    {
        if (!$this->app->hasBeenBootstrapped()) {
            $this->app->useEnvironmentPath(base_path('env'));
            if (gethostname() === 'softiso') {
                $this->app->loadEnvironmentFrom('softiso.env');
            } elseif (gethostname() === 'music'){
                $this->app->loadEnvironmentFrom('server.env');
            }
            parent::bootstrap();
        }
    }

}
