<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Test;
use App\User;

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
        $last_test = Test::orderBy('test_date', 'desc')->first();
        $waiver = User::select(
                DB::raw('-1 AS id'),
                DB::raw('id AS user_id'),
                DB::raw("'WAIVER' AS result"),
                DB::raw("'" . $last_test->test_date . "' AS test_date"),
                DB::raw("'' AS htmlClass")
            )
            ->where('id', $user->id)
            ->where('test_waiver_start_date', '<=', Carbon::now())
            ->where('test_waiver_end_date', '>=', Carbon::now())
            ->first();
        $test = Test::select('id', 'user_id', 'result', 'test_date')
            ->where('user_id', $user->id)
            ->where('result', '<>', 'UNREADABLE')
            ->orderBy('test_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($waiver) {
            $test = $waiver;
        } else {
            $test = $test ? $test : new Test();
        }

        return view('users/testing-optin', compact('user', 'test'));
    }
}
