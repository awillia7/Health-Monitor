<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Answer;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $screening = auth()->user()->getTodayScreening();
        
        $questions = Question::all()->sortBy('group_order')->sortBy('question_order');
        
        return view('home', compact('screening', 'questions'));
    }
}
