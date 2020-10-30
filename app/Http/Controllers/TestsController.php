<?php

namespace App\Http\Controllers;

use App\Test;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TestsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Test::class);

        $last_test = Test::orderBy('test_date', 'desc')->first();
        $test_query = User::with(['tests' => function ($q) {
            if (!(\Auth::user()->hasRole('TESTS_RESULTS'))) {
                $q->where('result', '<>', 'UNREADABLE');
            }
            $q->orderBy('test_date', 'DESC')
            ->orderBy('created_at', 'DESC');
        }])->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $tests = [];
        $positive_tests = [];
        $unreadable_tests = [];
        $negative_tests = [];
        foreach ($test_query as $user) {
            if ($user->tests->count() > 0 && \Auth::user()->hasRole('TESTS_RESULTS')) {
                if ($user->tests[0]->result == 'POSITIVE') {
                    array_push($positive_tests, [
                        "name" => $user->name,
                        "test_date" => $user->tests[0]->test_date,
                        "test_result" => $user->tests[0]->result,
                        "result_html_class" => $user->tests[0]->htmlClass,
                        "results_permission" => true
                    ]);
                } else if ($user->tests[0]->result == 'UNREADABLE') {
                    array_push($unreadable_tests, [
                        "name" => $user->name,
                        "test_date" => $user->tests[0]->test_date,
                        "test_result" => $user->tests[0]->result,
                        "result_html_class" => $user->tests[0]->htmlClass,
                        "results_permission" => true
                    ]);
                } else if ($user->tests[0]->result == 'NEGATIVE') {
                    array_push($negative_tests, [
                        "name" => $user->name,
                        "test_date" => $user->tests[0]->test_date,
                        "test_result" => $user->tests[0]->result,
                        "result_html_class" => $user->tests[0]->htmlClass,
                        "results_permission" => true
                    ]);
                }
            } else if ($user->tests->count() > 0 ||
                ($user->test_waiver_start_date <= Carbon::now() && $user->test_waiver_end_date >= Carbon::now())) {
                $test_date = $user->test_waiver_start_date <= Carbon::now() && $user->test_waiver_end_date >= Carbon::now() ? $last_test->test_date : $user->tests[0]->test_date;
                array_push($tests, [
                    "name" => $user->name,
                    "test_date" => $test_date,
                    "test_result" => null,
                    "result_html_class" => null,
                    "results_permission" => false
                ]);
            }
        }

        // Check if positive and negative test need to be combined
        if (\Auth::user()->hasRole('TESTS_RESULTS')) {
            $tests = array_merge($positive_tests, $unreadable_tests, $negative_tests);
        }
        
        return view('tests/index', ["tests" => json_encode($tests)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Test::class);

        // Create Test
        $test = new Test;
        $test->user_id = $request->user_id;
        $test->result = $request->result;
        $test->test_date = $request->test_date;
        $test->save();

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $test->id,
                'user_id' => $test->user_id,
                'result' => $test->result,
                'test_date' => $test->test_date,
                'message' => "Test result saved"
            ]);
        }

        return redirect()->route('tests/upload');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function edit(Test $test)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Test $test)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function destroy(Test $test)
    {
        //
    }
}
