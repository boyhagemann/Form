<?php

namespace Boyhagemann\Form\Element;

interface ElementInterface
{
	public function getName();
	public function getType();
	public function getFormType();
	public function getAttributes();
	public function getOptions();
	public function getRules();
}