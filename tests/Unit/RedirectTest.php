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
    const PATH = "/posts/all";
    const QUERY_STRING = "?bob=1&jack=2";

    function test_Throws404_WhenOldDoesntExist() {
        $this->assertThrows(ModelNotFoundException::class, function() {
            Redirect::for(self::ALTA_VISTA);
        });
    }

    function test_ReturnsNew_WhenOldExists() {
        $redirect = Redirect::for(self::YAHOO);

        $this -> assertEquals(self::GOOGLE, $redirect -> new);
    }

    function test_ThrowsAnException_WhenTheSameOldUrlIsAddedTwice() {
        $this -> assertThrows(QueryException::class, function() {
            $this -> createANewRedirect();
            $this -> createANewRedirect();
        });
    }

    function test_ReturnsNewWithPath_WhenPreservePathIsTrue() {
        $this -> createANewRedirectWithPath();
        $old = self::YAHOO . self::PATH;

        $redirect = Redirect::for($old);

        $this -> assertEquals(self::GOOGLE . self::PATH, $redirect -> new());
    }

    function test_ReturnsNewWithQueryString_WhenPreservePathIsTrue() {
        $this -> createANewRedirectWithQueryString();
        $old = self::YAHOO . self::QUERY_STRING;

        $redirect = Redirect::for($old);

        $this -> assertEquals(self::GOOGLE . self::QUERY_STRING, $redirect -> new());
    }

    function test_ReturnsNewWithPathAndQueryString_WhenPreservePathIsTrue() {
        $this -> createANewRedirectWithPathAndQueryString();
        $old = self::YAHOO . self::PATH . self::QUERY_STRING;

        $redirect = Redirect::for($old);

        $this -> assertEquals(self::GOOGLE . self::PATH . self::QUERY_STRING, $redirect -> new());
    }

    protected function setUp() {
        parent::setUp();

        $this -> createANewRedirect();
    }

    private function createANewRedirect() {
        $startData = [
            "old" => self::YAHOO, 
            "new" => self::GOOGLE,
            "code" => 302
        ];
        $redirect = new Redirect($startData);
        $redirect -> save();
    }

    private function createANewRedirectWithPath() {
        $startData = [
            "old" => self::YAHOO . self::PATH, 
            "new" => self::GOOGLE,
            "code" => 302,
            "preserve_path" => true,
        ];
        $redirect = new Redirect($startData);

        $redirect -> save();
    }

    private function createANewRedirectWithQueryString() {
        $startData = [
            "old" => self::YAHOO . self::QUERY_STRING, 
            "new" => self::GOOGLE,
            "code" => 302,
            "preserve_path" => true,
        ];
        $redirect = new Redirect($startData);

        $redirect -> save();
    }

    private function createANewRedirectWithPathAndQueryString() {
        $startData = [
            "old" => self::YAHOO . self::PATH . self::QUERY_STRING, 
            "new" => self::GOOGLE,
            "code" => 302,
            "preserve_path" => true,
        ];
        $redirect = new Redirect($startData);

        $redirect -> save();
    }
}