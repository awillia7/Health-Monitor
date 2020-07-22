<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreScreening;
use App\Screening;
use App\Answer;
use App\Question;

class ScreeningsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Screening $screening)
    {
        //dd($screening->id);
        $res = Screening::where('id', $screening->id)->with(['answers' => function($answer) {
            $answer->with(['question']);
        }, 'user'])->first();

        return view('screenings.show', ['screening' => $res]);
    }
}
