<?php

namespace Boyhagemann\Form;

use Illuminate\Support\ServiceProvider;
use Hostnet\FormTwigBridge\Builder;
use Hostnet\FormTwigBridge\TranslatorBuilder;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider;
use App;

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
		// Add some default elements to the container
		$this->app->singleton('Boyhagemann\Form\FormElementContainer', function() {
			return new FormElementContainer;
		});

		// Create an alias for the formbuilder class
		$this->app->bind('formbuilder', 'Boyhagemann\Form\FormBuilder');
                
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