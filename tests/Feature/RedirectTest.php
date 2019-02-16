<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Redirect;

class RedirectTests extends TestCase {
    use RefreshDatabase;

    const TEST = "http://localhost";
    const YAHOO = "https://yahoo.com";
    const ALTA_VISTA = "http://altavista.com";
    const FOUROHFOUR = "Page Not Found";

    public function test404() {
       $response = $this->get('/');
       $content = $response -> content();

       $response -> assertStatus(404); 
       $this -> assertContains(self::FOUROHFOUR, $content);
    }

    public function test301() {
        $this -> createARedirect(self::ALTA_VISTA, 301);
        
        $response = $this->get('/');
        $content = $response -> content();
        $response_string = "Redirecting to " . self::ALTA_VISTA;

        $response -> assertStatus(301);
        $this -> assertContains($response_string, $content);
    }

    public function test302() {
        $this -> createARedirect(self::YAHOO, 302);

        $response = $this->get('/');
        $content = $response -> content();
        $response_string = "Redirecting to " . self::YAHOO;

        $response -> assertStatus(302);
        $this -> assertContains($response_string, $content);
    }

    private function createARedirect($new, $code) {
        // The redirect is always from http://localhost when testing
        $startData = [
            "old_url" => self::TEST, 
            "new_url" => $new,
            "code" => $code
        ];
        $redirect = new Redirect($startData);
        $redirect -> save();
    }
}