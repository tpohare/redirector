<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Redirect;

class RedirectController extends Controller {
    function show(Request $request): RedirectResponse
    {
        $redirect = Redirect::for($request->fullUrl());

        return redirect($redirect -> new(), $redirect -> code);
    }
}