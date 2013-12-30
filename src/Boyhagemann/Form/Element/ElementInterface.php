<?php

namespace Boyhagemann\Form\Element;

interface ElementInterface
{
	public function name($name);
	public function label($label);
	public function value($value);
	public function view($view);
	public function attr($attr, $value);
	public function help($help);
	public function option($option, $value);
	public function required($required = true);
	public function disabled($disable = true);
	public function map($map);
	public function rules($rules);

	public function getName();
	public function getLabel();
	public function getValue();
	public function getView();
	public function getOptions();
	public function getAttributes();
	public function getHelp();
	public function getRules();
	public function isRequired();
	public function isDisabled();
	public function isMapped();
	public function hasRule($rule);
	public function removeRule($rule);

	public function getValidationState();
	public function withSuccess();
	public function withError();
	public function withWarning();

}