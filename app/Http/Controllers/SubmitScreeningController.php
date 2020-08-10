<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SubmitScreening;
use App\Screening;
use App\Answer;
use App\Mail\ScreeningUncleared;
use Illuminate\Support\Facades\Mail;

class SubmitScreeningController extends Controller
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
    public function __invoke(SubmitScreening $request)
    {
        // Create Screening
        $screening = new Screening;
        $screening->score = 0;
        $screening->fail_score = env('HEALTH_MONITOR_FAIL_SCORE');
        $screening->user_id = $request->user()->id;
        $screening->save();

        // Create Answers
        $screeningScore = 0;
        foreach ($request->all() as $key => $value) {
            $questionIds = explode("_", $key);
            if ($questionIds[0] === "question") {
                $answer = new Answer;
                $answer->screening_id = $screening->id;
                $answer->question_id = $questionIds[1];
                $answer->value = $value;
                $answer->save();
                $screeningScore += $value;
            }
        }

        // Update Screening score
        $screening->score = $screeningScore;
        $screening->save();

        // Send email if the screening failed
        if ($screening->score >= $screening->fail_score) {
            $emails = explode(";", env('HEALTH_MONITOR_ALERT_EMAILS'));
            Mail::to($emails)->send(new ScreeningUncleared($screening));
        }

        return redirect()->route('home');
    }
}
