<?php

namespace Boyhagemann\Form;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->package('boyhagemann/form');
    }

    public function boot()
    {
		// Create the element container as a singleton
		$this->app->singleton('Boyhagemann\Form\FormElementContainer', function() {
			return new FormElementContainer;
		});

		// Create the formbuilder class
		$this->app->bind('Boyhagemann\Form\FormBuilder', function($app) {
			return new FormBuilder($app['Boyhagemann\Form\FormElementContainer'], $app['view'], $app['events']);
		});

		// Create an alias for the formbuilder class
		$this->app->alias('Boyhagemann\Form\FormBuilder', 'formbuilder');


		$container = $this->app->make('Boyhagemann\Form\FormElementContainer');
		$container->resolvingAny(function($element) use($container) {

			if(!$element instanceof Element\ModelElement) {
				return;
			}

			$element->container($container);
			return $element;
		});

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('form');
    }

}