<?php

namespace App\Cms\Redirects;

use Code16\Sharp\EntityList\SharpEntityList;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;
use App\Redirect as RedirectModel;


class Dashboard extends SharpEntityList {

    function buildListDataContainers()
    {
        $this->addDataContainer(
            EntityListDataContainer::make("old_url")
                ->setLabel("From: ")
                ->setSortable()
                ->setHtml()
        )->addDataContainer(
            EntityListDataContainer::make("new_url")
                ->setLabel("To: ")
                ->setSortable()
                ->setHtml()
        )->addDataContainer(
            EntityListDataContainer::make("code")
                ->setLabel("Code: ")
                ->setSortable()
                ->setHtml()
        )->addDataContainer(
            EntityListDataContainer::make("preserve_path")
                ->setLabel("Keep Path: ")
                ->setSortable()
                ->setHtml()
        );
    }

     function buildListLayout()
    {
        $this->addColumn("old_url", 3, 3)
            ->addColumn("new_url", 3,3)
            ->addColumn("code", 3, 3)
            ->addColumn("preserve_path", 3, 3);
    }

     function getListData(EntityListQueryParams $params)
    {
        $redirects = RedirectModel::orderBy(
            $params->sortedBy(), $params->sortedDir()
        );

        collect($params -> searchWords()) -> each( function($word) use($redirects) {
            $redirects -> where(function ($query) use ($word) {
                $query -> orWhere('new_url', 'like', $word) 
                        -> orWhere('old_url', 'like', $word);
            });
        });


        return  $this -> transform(
            $redirects->paginate(20)
        );
    }

    function buildListConfig()
    {
        $this->setInstanceIdAttribute("id")
            ->setSearchable()
            ->setDefaultSort("new_url", "asc")
            ->setPaginated();
    }
}