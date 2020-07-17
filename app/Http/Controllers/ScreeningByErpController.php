<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        // $res = Screening::where('id', $screening->id)->with(['answers' => function($answer) {
        //     $answer->with(['question']);
        // }])->first();

        $user = User::where('erp_id', str_pad($request->user, 7, '0', STR_PAD_LEFT))->first();
        if (!$user) {
            return response()->json([], 404);
        }

        $screening = User::where('erp_id', str_pad($request
            ->user, 7, '0', STR_PAD_LEFT))
            ->first()->getTodayScreening();
        
        $status = null;
        if (!$screening) {
            $status = null;
        } else {
            $status = $screening->fail_score > $screening->score ? 'OPEN' : 'CLOSED';
        }

        return response()->json([
            'id' => $request->user,
            'status' => $status
        ]);
    }
}
