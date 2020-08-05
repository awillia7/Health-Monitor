<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;

class UpdateAllQuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ADMIN');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            if ($value) {
                $fieldKey = explode("_", $key);
                
                if ($fieldKey[0] === "text") {
                    $question = Question::find($fieldKey[1]);
                    $question->text = $value;
                    $question->save();
                } else if ($fieldKey[0] === "value" && is_numeric($value) && $value > 0) {
                    $question = Question::find($fieldKey[1]);
                    $question->value = $value;
                    $question->save();
                } else if ($fieldKey[0] === "group-text") {
                    Question::where('group_order', '=', $fieldKey[1])->update(['group_text' => $value]);
                }
            }
        }

        return redirect()->route('questions.index');
    }
}
