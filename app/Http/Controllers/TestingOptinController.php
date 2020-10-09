<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;

class TestingOptinController extends Controller
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
        $user = User::find($request->user()->id);

        if ($user && $request->optin_date) {
            $user->test_optin_date = $request->optin_date;
            $user->test_print_date = $request->print_date;
            $user->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'id' => $user->id,
                    'test_optin_date' => $user->formatted_test_optin_date,
                    'test_print_date' => $user->formatted_test_print_date,
                    'message' => "You are now Opted In for Testing"
                ]);
            }

            return redirect()->route('testing.optin');
        } else if ($user->test_optin_date && $request->print_date) {
            $user->test_print_date = $request->print_date;
            $user->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'test_print_date' => $user->formatted_test_print_date,
                    'message' => "Your label request date has been updated"
                ]);
            }

            return redirect()->route('testing.optin');
        }

        if (!$user) {
            $message = "User not found";
        } else {
            $message = "Signature not accepted";
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message
            ], 406);
        }

        return redirect()->route('testing.optin');
    }
}
