<?php

namespace Boyhagemann\Form\Element;

class CheckableElement extends InputElement
{
	/**
	 * @param array $choices
	 * @return $this
	 */
	public function choices($choices)
	{
		$this->options['choices'] = $choices;
		return $this;
	}

}