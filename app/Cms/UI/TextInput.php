<?php

namespace App\Cms\UI;

use Code16\Sharp\Form\Fields\SharpFormTextField;

class TextInput {
    static function make($name, $label) 
    {
        return SharpFormTextField::make($name) -> setLabel($label);
    }
}