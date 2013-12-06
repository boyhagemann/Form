<?php

namespace Boyhagemann\Form\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Boyhagemann\Form\FormBuilder;
use Session, Route, Request;

class SaveFormStateInSession
{
	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param Events $events
	 */
	public function subscribe(Events $events)
	{
		$events->listen('form.formBuilder.build.before', array($this, 'listenToRouteAction'));
		$events->listen('form.formBuilder.build.before', array($this, 'setDefaults'));
	}

	/**
	 * Seed the form with defaults that are stored in the session
	 *
	 * @param FormBuilder $fb
	 */
	public function listenToRouteAction(FormBuilder $fb)
	{
		$key = $this->getKey($fb);

		if(Route::currentRouteName() == $fb->getRoute() && Request::getMethod() == $fb->getMethod()) {



		}

	}

	/**
	 * Seed the form with defaults that are stored in the session
	 *
	 * @param FormBuilder $fb
	 */
	public function setDefaults(FormBuilder $fb)
	{
		$key = $this->getKey($fb);

		if(!Session::has($key)) {
			return;
		}

		$fb->defaults(Session::get($key));
	}

	/**
	 * @param FormBuilder $fb
	 * @return string
	 */
	protected function getKey(FormBuilder $fb)
	{
		return 'form-' . $fb->getName();
	}
}