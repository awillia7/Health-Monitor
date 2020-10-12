<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestUploadFormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:TESTS_IMPORT');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('tests/upload');
    }
}
