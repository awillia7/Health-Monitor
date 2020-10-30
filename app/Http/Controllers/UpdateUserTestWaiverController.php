<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class UpdateUserTestWaiverController extends Controller
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
    public function __invoke(Request $request, $user)
    {
        $start_date = $request->test_waiver_start_date ? Carbon::create($request->test_waiver_start_date) : null;
        $end_date = $request->test_waiver_end_date ? Carbon::create($request->test_waiver_end_date) : null;
        $model = User::find($user);

        if (!$model) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "User not found"
                ], 406);
            }

            throw ValidationException::withMessage(['user' => 'User not found']);
        }

        if (!$start_date && $end_date) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Waiver Start Date is missing"
                ], 406);
            }

            throw ValidationException::withMessage(['test_waiver_start_date' => "Waiver Start Date is missing"]);
        }

        if ($start_date && !$end_date) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Waiver End Date is missing"
                ], 406);
            }

            throw ValidationException::withMessage(['test_waiver_end_date' => "Waiver End Date is missing"]);
        }

        if ($start_date && $end_date && $start_date->greaterThan($end_date)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => "Waiver End Date cannot be before Waiver Start Date"
                ], 406);
            }

            throw ValidationException::withMessage(['test_waiver_end_date' => "Waiver End Date cannot be before Waiver Start Date"]);
        }

        $model->test_waiver_start_date = $start_date;
        $model->test_waiver_end_date = $end_date;
        $model->save();

        if ($request->expectsJson()) {
            return response()->json([
                'user' => $model,
                'message' => "Users test waiver dates updated"
            ]);
        }

        return redirect()->route('users.testing-waivers');
    }
}
