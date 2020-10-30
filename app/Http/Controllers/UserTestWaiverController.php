<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;

class UserTestWaiverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:TESTS_WAIVERS');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $users = User::where('test_waiver_start_date', '<=', Carbon::today())
        ->where('test_waiver_end_date', '>=', Carbon::today())->get();

        return view('users.testing-waivers', compact('users'));
    }
}
