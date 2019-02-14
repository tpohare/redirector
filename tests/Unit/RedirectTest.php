<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Redirect;
use Jchook\AssertThrows\AssertThrows;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class RedirectTests extends TestCase
{
    use RefreshDatabase;
    use AssertThrows;

    const GOOGLE = "https://google.com";
    const YAHOO = "https://yahoo.com";
    const ALTA_VISTA = "http://altavista.com";

    protected function setUp() 
    {
        parent::setUp();

        $this -> createANewRedirect();
    }

    private function createANewRedirect() 
    {
        $startData = [
            "old" => self::YAHOO, 
            "new" => self::GOOGLE,
            "code" => 302
        ];
        $redirect = new Redirect($startData);
        $redirect -> save();
    }
    
    function test_Throws404_WhenOldDoesntExist() 
    {
        $this->assertThrows(ModelNotFoundException::class, function() {
            Redirect::for(self::ALTA_VISTA);
        });
    }

    function test_ReturnsNew_WhenOldExists() 
    {
        $redirect = Redirect::for(self::YAHOO);

        $this -> assertEquals(self::GOOGLE, $redirect -> new);
    }

    function test_ThrowsAnException_WhenTheSameOldUrlIsAddedTwice() 
    {
        $this -> assertThrows(QueryException::class, function() {
            $this -> createANewRedirect();
            $this -> createANewRedirect();
        });
    }
}