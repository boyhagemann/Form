<?php

namespace Boyhagemann\Form;

use Illuminate\Container\Container;

class FormElementContainer extends Container
{
	/**
	 * The registered type aliases.
	 *
	 * @var array
	 */
	protected $aliases = array(
		'text' 			=>	'Boyhagemann\Form\Element\Text',
		'hidden' 		=>	'Boyhagemann\Form\Element\Hidden',
		'password' 		=>	'Boyhagemann\Form\Element\Password',
		'textarea' 		=>	'Boyhagemann\Form\Element\Textarea',
		'select' 		=> 	'Boyhagemann\Form\Element\Select',
		'modelSelect' 	=>	'Boyhagemann\Form\Element\ModelSelect',
		'checkbox' 		=> 	'Boyhagemann\Form\Element\Checkbox',
		'modelCheckbox' =>	'Boyhagemann\Form\Element\ModelCheckbox',
		'radio' 		=> 	'Boyhagemann\Form\Element\Radio',
		'modelRadio' 	=> 	'Boyhagemann\Form\Element\ModelRadio',
	);
}