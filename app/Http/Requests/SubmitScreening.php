<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Question;

class SubmitScreening extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $questions = Question::all()->sortBy('group_order')->sortBy('question_order');
        foreach ($questions as $question) {
            $index = "question_" . $question->id;
            $rules[$index] = 'required';
            if ($question->group_text) {
                $index = "group_" . $question->group_order;
                $rules[$index] = 'required';
            }
        }

        return $rules;
    }
}
