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
		$this->app->singleton('Boyhagemann\Form\FormElementContainer', function($app) {

			$container = new FormElementContainer;
			
			$container->bind('text', 			'Boyhagemann\Form\Element\Text');
			$container->bind('password', 		'Boyhagemann\Form\Element\Password');
			$container->bind('textarea', 		'Boyhagemann\Form\Element\Textarea');
			$container->bind('select', 			'Boyhagemann\Form\Element\Select');
			$container->bind('modelSelect',		'Boyhagemann\Form\Element\ModelSelect');
			$container->bind('checkbox', 		'Boyhagemann\Form\Element\Checkbox');
			$container->bind('modelCheckbox',	'Boyhagemann\Form\Element\ModelCheckbox');
			$container->bind('radio', 			'Boyhagemann\Form\Element\Radio');
			$container->bind('modelRadio', 		'Boyhagemann\Form\Element\ModelRadio');
			
			return $container;
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