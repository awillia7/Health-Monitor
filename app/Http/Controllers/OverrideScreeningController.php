<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Screening;
use Carbon\Carbon;

class OverrideScreeningController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:SCREENINGS_OVERRIDE');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Screening $screening)
    {        
        if ($screening->override_user_id) {
            $message = "Screening has already been overwritten.";
        } else {
            $screening->override_user_id = request()->user()->id;
            $screening->override_at = Carbon::now();
            $screening->save();
            
            $message = "Screening has been overwritten.";
        }

        if (request()->expectsJson()) {
            return response()->json([
                "screening" => [
                    "id" => $screening->id,
                    "score" => $screening->score,
                    "fail_score" => $screening->fail_score,
                    "user_name" => $screening->user->name,
                    "override_user_id" => $screening->override_user_id,
                    "override_at" => $screening->override_at
                ],
                "message" => $message
            ]);
        }

        return redirect()->route('screenings.index');
    }
}
