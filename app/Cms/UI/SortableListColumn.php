<?php

namespace App\Cms\UI;

use Code16\Sharp\EntityList\Containers\EntityListDataContainer;

class SortableListColumn {
    static function make($column, $label) {
        return EntityListDataContainer::make($column) 
            -> setLabel($label) 
            -> setSortable() 
            -> setHtml();
    }
} 