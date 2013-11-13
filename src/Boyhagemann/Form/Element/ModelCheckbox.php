<?php

namespace Boyhagemann\Form\Element;

class ModelCheckbox extends ModelElement implements Type\MultipleChoice
{
	protected $view = 'form::element.checkboxes';
	protected $value = array();
	protected $attributes = array();
}