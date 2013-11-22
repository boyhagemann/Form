<?php

namespace Boyhagemann\Form;

interface Element
{
	public function name($name);
	public function label($label);
	public function value($value);
	public function required($required = true);
	public function attr($attr, $value);
	public function option($option, $value);
	public function help($help);
	public function disabled($disable = true);
	public function rules($rules);
	public function view($view);
	public function map($map);

	public function getName();
	public function getLabel();
	public function getValue();
	public function getOptions();
	public function getAttributes();
	public function getHelp();
	public function getRules();
	public function getView();
	public function isMapped();
	public function isRequired();
	public function isDisabled();

	public function hasRule($rule);
	public function hasSuccess();
	public function hasError();
	public function hasWarning();
	public function getValidationState();
}