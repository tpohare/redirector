<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model {
    protected $fillable = ["old","new","code"];

    static function for($old) {
        return Redirect::where("old", $old) -> firstOrFail();
    }
}