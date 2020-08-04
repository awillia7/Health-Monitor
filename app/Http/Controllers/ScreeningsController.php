<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreScreening;
use App\Screening;
use App\Answer;
use App\Question;
use Carbon\Carbon;

class ScreeningsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Screening $screening)
    {
        $res = Screening::where('id', $screening->id)->with(['answers' => function($answer) {
            $answer->with(['question']);
        }, 'user'])->first();

        return view('screenings.show', ['screening' => $res]);
    }

    public function index()
    {
        $this->authorize('index', Screening::class);

        $screenings = Screening::with(['user' => function ($q) {
            $q->orderBy('name', 'asc');
        }])->whereDate('screenings.created_at', Carbon::today())
        ->where('score', '>=', \DB::raw('fail_score'))->get();

        return view('screenings.index', compact('screenings'));
    }
}
