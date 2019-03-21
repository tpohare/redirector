<?php

namespace App\Cms\Redirects;

use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;
use App\Redirect as RedirectModel;
use App\Cms\UI\SortableListColumn;


class Dashboard extends SharpEntityList {

    function buildListDataContainers()
    {
        $this   -> addDataContainer(SortableListColumn::make("old_url", "From: "))
                -> addDataContainer(SortableListColumn::make("new_url", "To: "))
                -> addDataContainer(SortableListColumn::make("code", "Code: "))
                -> addDataContainer(SortableListColumn::make("preserve_path", "Keep Path: "));
    }

     function buildListLayout()
    {
        $this   -> addColumn("old_url", 3, 3)
                -> addColumn("new_url", 3,3)
                -> addColumn("code", 3, 3)
                -> addColumn("preserve_path", 3, 3);
    }

     function getListData(EntityListQueryParams $params)
    {
        $redirects = RedirectModel::orderBy(
            $params->sortedBy(), $params->sortedDir()
        );

        $search_words = $params -> searchWords();
        
        if (0 < count($search_words)) {
            $redirects = $this -> filterRedirectsBySearchValue($search_words, $redirects);
        }

        return  $this -> transform($redirects -> paginate(20));
    }

    function filterRedirectsBySearchValue($search_words, $redirects) 
    {
        $search_words = collect($search_words);

        $search_words -> each(function($word) use ($redirects){
            $redirects -> where(function($query) use ($word){
                $query  -> orWhere('new_url', 'like', $word)
                        -> orWhere('old_url', 'like', $word); 
            });
        });

        return $redirects;

    }

    function buildListConfig()
    {
        $this   -> setInstanceIdAttribute("id")
                -> setSearchable()
                -> setDefaultSort("new_url", "asc")
                -> setPaginated();
    }
}