<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model {
    protected $fillable = ["old","new","code", "preserve_path"];

    static function for($old) {
        return Redirect::where("old", $old) -> firstOrFail();
    }

    public function new() {
        if ($this -> preserve_path)
            return $this -> new . $this -> path($this -> old);

        return $this -> new;
    }

    private function path($url) {
        $url_components = \parse_url($url);
        $path = isset($url_components["path"]) ? $url_components["path"] : "";
        $query_string = isset($url_components["query"]) ? "?" . $url_components["query"] : "";

        return $path . $query_string;
    }
}