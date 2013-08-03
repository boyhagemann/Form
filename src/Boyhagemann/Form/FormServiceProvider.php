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
        $this->package('form', 'form');
                
        App::bind('Symfony\Component\Form\FormBuilder', function($app) {
            $csrf = new DefaultCsrfProvider('change this token');
            $translator_builder = new TranslatorBuilder();
            $translator_builder->setLocale('nl_NL'); // Uncomment if you want a non-english locale

            $builder = new Builder();
            $builder->setCsrfProvider($csrf);
            $builder->setTranslator($translator_builder->build());

            return $builder->buildFormFactory()->createBuilder();
        });
    }

    public function boot()
    {

        require_once(__DIR__ . '/../../form-macros.php');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}