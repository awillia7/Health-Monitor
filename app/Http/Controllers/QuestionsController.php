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
        $questions = Question::all()->sortBy('group_order')->sortBy('question_order');

        return view('questions.index', compact('questions'));
    }
}
