<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ScreeningByErpController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = User::where('erp_id', str_pad($request->user, 7, '0', STR_PAD_LEFT))->first();
        if (!$user) {
            return response()->json(null, 404);
        }

        $screening = User::where('erp_id', str_pad($request
            ->user, 7, '0', STR_PAD_LEFT))
            ->first()->getTodayScreening();
        
        $status = null;
        if (!$screening) {
            $status = null;
        } else {
            if ($screening->override_user_id || $screening->score < $screening->fail_score) {
                $status = 'CLEARED';
            } else {
                $status = 'NOT CLEARED';
            }
        }

        return response()->json([
            'id' => $request->user,
            'status' => $status
        ]);
    }
}
