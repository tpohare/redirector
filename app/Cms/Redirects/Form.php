<?php

namespace App\Cms\Redirects;

use Code16\Sharp\Form\SharpForm;
use App\Redirect;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;
use App\Cms\UI\TextInput;
use App\Cms\UI\Checkbox;


class Form extends SharpForm
{
    use WithSharpFormEloquentUpdater;

    function buildFormFields()
    {
        $this   -> addField(TextInput::make ("old_url", "From: "))
                -> addField(TextInput::make ("new_url", "To: "))
                -> addField(TextInput::make ("code", "Code: "))
                -> addField(Checkbox::make  ("preserve_path", "Preserve Path", "Preserve Path: "));
    }

    function buildFormLayout()
    {
        $this->addColumn(6, function(FormLayoutColumn $column) {
            $column
                -> withSingleField("old_url") 
                -> withSingleField("new_url")
                -> withSingleField("code")
                -> withSingleField("preserve_path");
            }
        );
    }

    function find($id): array
    {
        return $this->transform(Redirect::findOrFail($id));
    }

    function update($id, array $data)
    {
        $instance = $id ? Redirect::findOrFail($id) : new Redirect;

        $id = tap($instance, function($redirect) use($data) {
            $real_data = [
                "old_url" => $data["old_url"],
                "new_url" => $data["new_url"],
                "code" => $data["code"],
                "preserve_path" => $data["preserve_path"],
            ];
            $this->save($redirect, $real_data);
        })->id;

        return $id;
    }

    function delete($id)
    {
        Redirect::destroy($id);
    }
}