<?php

namespace Boyhagemann\Form\Element\Type;

interface MultipleChoice
{
	public function choices(Array $choices);
	public function getChoices();
}