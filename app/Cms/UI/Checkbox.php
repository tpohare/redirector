<?php

namespace App\Cms\UI;

use Code16\Sharp\Form\Fields\SharpFormCheckField;

class Checkbox {
    static function make($name, $option, $label) 
    {
        return SharpFormCheckField::make($name, $option) -> setLabel($label);
    }
}