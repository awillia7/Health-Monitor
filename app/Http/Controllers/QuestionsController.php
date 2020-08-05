<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ADMIN');
    }

    public function index()
    {
        $questions = Question::orderBy('group_order')->orderBy('question_order')->get();

        return view('questions.index', [
            'questions' => $questions,
            'fail_score' => getenv('HEALTH_MONITOR_FAIL_SCORE')
        ]);
    }
}
