<?php

namespace Boyhagemann\Form\Element\Type;

interface MultipleChoice
{
	public function choices($choices);
	public function getChoices();
}