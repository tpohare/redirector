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
    const ALTA_VISTA = "https://altavista.com";
    const ALTA_VISTA_PATTERN = "https://altavista.com/$2/$1";
    const PATH = "/posts/all";
    const QUERY_STRING = "?bob=1&jack=2";

    protected function setUp() {
        parent::setUp();

        $this -> createANewRedirect();
    }

    public function test_Throws404_WhenOldDoesntExist() {
        $this->assertThrows(ModelNotFoundException::class, function() {
            Redirect::for(self::ALTA_VISTA);
        });
    }

    public function test_ReturnsNew_WhenOldExists() {
        $redirect = Redirect::for(self::YAHOO);

        $this -> assertEquals(self::GOOGLE, $redirect -> new);
    }

    public function test_ThrowsAnException_WhenTheSameOldUrlIsAddedTwice() {
        $this -> assertThrows(QueryException::class, function() {
            $this -> createANewRedirect();
            $this -> createANewRedirect();
        });
    }

    public function test_ReturnsNewWithPath_WhenPreservePathIsTrue() {
        $this -> createANewRedirect(self::PATH, null, true, 301);
        $old = self::YAHOO . self::PATH;

        $redirect = Redirect::for($old);

        $this -> assertEquals(self::GOOGLE . self::PATH, $redirect -> new());
    }

    public function test_ReturnsNewWithQueryString_WhenPreservePathIsTrue() {
        $this -> createANewRedirect(null, self::QUERY_STRING, true, 302);
        $old = self::YAHOO . self::QUERY_STRING;

        $redirect = Redirect::for($old);

        $this -> assertEquals(self::GOOGLE . self::QUERY_STRING, $redirect -> new());
    }

    public function test_ReturnsNewWithPathAndQueryString_WhenPreservePathIsTrue() {
        $this -> createANewRedirect(self::PATH, self::QUERY_STRING, true);
        $old = self::YAHOO . self::PATH . self::QUERY_STRING;

        $redirect = Redirect::for($old);

        $this -> assertEquals(self::GOOGLE . self::PATH . self::QUERY_STRING, $redirect -> new());
    }

    public function test_ReplacesPlaceholdersWithPath() {
        $old =  $this -> buildUrl(self::YAHOO, self::PATH, null);
        $startData = [
            "old" => $old, 
            "new" => self::ALTA_VISTA_PATTERN,
            "code" => 301,
            "preserve_path" => false,
        ];

        (new Redirect($startData)) -> save();

        $redirect = Redirect::for($old);
        $new = "https://altavista.com/all/posts";
        $this -> assertEquals($new, $redirect -> new());
    }

    private function createANewRedirect($path = null, $queryString = null, $preserve_path = false, $code = 302) {
        $startData = [
            "old" => $this -> buildUrl(self::YAHOO, $path, $queryString), 
            "new" => self::GOOGLE,
            "code" => $code,
            "preserve_path" => $preserve_path,
        ];

        $redirect = new Redirect($startData);
        $redirect -> save();
    }

    private function buildUrl($base, $path, $queryString) {
        $url = $base;

        if ($path)
            $url .= $path;

        if ($queryString)
            $url .= $queryString;

        return $url;
    }
}