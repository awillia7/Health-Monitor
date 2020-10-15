<?php

namespace App\Http\Controllers;

use App\Test;
use App\User;
use Illuminate\Http\Request;

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

        $test_query = User::with(['tests' => function ($q) {
            $q->orderBy('test_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->first();
        }])->whereNotNull('test_optin_date')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $tests = [];
        foreach ($test_query as $user) {
            $test_result = \Auth::user()->hasRole('TESTS_RESULTS');
            array_push($tests, [
                "name" => $user->name,
                "test_date" => $user->tests->count() ? $user->tests[0]->test_date : null,
                "test_result" => ($test_result && $user->tests->count()) ? $user->tests[0]->result : null,
                "result_html_class" => ($test_result && $user->tests->count()) ? $user->tests[0]->htmlClass : null,
                "results_permission" => $test_result
            ]);
        }

        // return view('tests/index', compact('tests'));
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
