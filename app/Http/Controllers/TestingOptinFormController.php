<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Test;

class TestingOptinFormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = \Auth::user();
        $test = Test::where('user_id', $user->id)
            ->where('result', '<>', 'UNREADABLE')
            ->orderBy('test_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();
        $test = $test ? $test : new Test();

        return view('users/testing-optin', compact('user', 'test'));
    }
}
