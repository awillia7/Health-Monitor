<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\PositiveTests;
use Illuminate\Support\Facades\Mail;

class EmailTestResults extends Controller
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
        if ($request->tests) {
            $to_emails = explode(";", env('HEALTH_MONITOR_ALERT_EMAILS'));
            
            Mail::to($to_emails)
                ->send(new PositiveTests($request->tests));

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Email of Positive Tests sent"
                ], 204);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "No Positive Tests Found"
            ], 204);
        }

        return view('tests/upload');
    }
}
