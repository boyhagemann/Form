<?php

namespace Boyhagemann\Form\Element;

use Form;

class Hidden extends AbstractElement implements Type\Input
{
    /**
     * 
     * @return string
     */
    public function getView()
    {
        if($this->view) {
            return $this->view;
        }
        
        return Form::hidden($this->getName(), $this->getValue(), $this->getAttributes());
    }

}