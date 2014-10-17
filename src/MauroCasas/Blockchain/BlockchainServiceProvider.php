<?php namespace MauroCasas\Blockchain {

	use Illuminate\Support\ServiceProvider;

    /**
     * @package Blockchain
     * @version 0.1
     * @author Mauro Casas <casas.mauroluciano@gmail.com>
     */

	class BlockchainServiceProvider extends ServiceProvider {

		/**
		 * Indicates if loading of the provider is deferred.
		 *
		 * @var bool
		 */
		protected $defer = false;

		public function boot(){
			$this->package('maurocasas/blockchain');
		}

		/**
		 * Register the service provider.
		 *
		 * @return void
		 */
		public function register()
		{
			$this->app['blockchain'] = $this->app->share(function($app){
				return new Blockchain($app['config']->get('blockchain::config'));
			});

			$this->app->bind('MauroCasas\Blockchain\Blockchain', function($app){
				return $app['blockchain'];
			});

			$this->app->booting(function(){
				$loader = \Illuminate\Foundation\AliasLoader::getInstance();
				$loader->alias('Blockchain', 'MauroCasas\Blockchain\Facades\Blockchain');
			});
		}

		/**
		 * Get the services provided by the provider.
		 *
		 * @return array
		 */
		public function provides()
		{
			return array('blockchain');
		}

	}

}