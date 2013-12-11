<?php

namespace Boyhagemann\Form\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Boyhagemann\Form\FormBuilder;
use Illuminate\Session\Store as Session;

/**
 * 
 * If there are any validation errors in a previous
 * form post, then set the errors in the current form.
 * This will show a help text with each element that
 * contains errors.
 */
class FillFormWithErrorsFromSession
{
    protected $session;
    
    /**
     * 
     * @param \Illuminate\Session\Store $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    
    /**
	 * Register the listeners for the subscriber.
	 *
	 * @param Events $events
	 */
	public function subscribe(Events $events)
	{
		$events->listen('form.formBuilder.build.before', array($this, 'fillErrors'));
	}

	/**
	 * Seed the form with defaults that are stored in the session
	 *
	 * @param FormBuilder $fb
	 */
	public function fillErrors(FormBuilder $fb)
	{
        if($this->session->get('errors')) {
            $fb->errors($this->session->get('errors'));
        }
	}

}