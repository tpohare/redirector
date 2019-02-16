<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Redirect extends Model {
    use SoftDeletes;

    protected $fillable = ["old_url", "new_url", "code", "preserve_path"];
    protected $dates = ['deleted_at'];

    static function for($old) {
        return Redirect::where("old_url", $old) -> firstOrFail();
    }

    public function new() {
        if ($this -> preserve_path)
            return $this -> new_url . $this -> path($this -> old_url);

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
        $new_components =  \parse_url($this -> new_url)["path"] ?? "";
        $old_components = $this -> oldComponents();
        $replaced_path = strtr($new_components, $old_components);

        return str_replace($new_components, $replaced_path, $this -> new_url);
    }

    private function oldComponents() {
        $components = [];
        $old_components = explode("/", \parse_url($this -> old_url)["path"] ?? "");

        foreach($old_components as $index => $component) {
            $components["$" . $index] = $component;
        }

        return $components;

    }
}