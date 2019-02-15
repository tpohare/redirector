<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model {
    protected $fillable = ["old", "new", "code", "preserve_path"];

    static function for($old) {
        return Redirect::where("old", $old) -> firstOrFail();
    }

    public function new() {
        if ($this -> preserve_path)
            return $this -> new . $this -> path($this -> old);

        $url = $this -> replaceComponents();
        return $url;
    }

    private function path($url) {
        $url_components = \parse_url($url);
        $path = isset($url_components["path"]) ? $url_components["path"] : "";
        $query_string = isset($url_components["query"]) ? "?" . $url_components["query"] : "";

        return $path . $query_string;
    }

    private function replaceComponents() {
        $new_components =  \parse_url($this -> new)["path"] ?? "";
        $old_components = $this -> oldComponents();
        $replaced_path = strtr($new_components, $old_components);

        return str_replace($new_components, $replaced_path, $this -> new);
    }

    private function oldComponents() {
        $components = [];
        $old_components = explode("/", \parse_url($this -> old)["path"] ?? "");

        foreach($old_components as $index => $component) {
            $components["$" . $index] = $component;
        }

        return $components;

    }
}