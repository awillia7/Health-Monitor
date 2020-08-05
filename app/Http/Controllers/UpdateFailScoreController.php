<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UpdateFailScoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'failScore' => 'required|numeric|gt:0'
        ]);

        $this->setEnv('HEALTH_MONITOR_FAIL_SCORE', $request->failScore);

        $message = "The fail score has been updated";
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message
            ]);
        }

        return redirect()->route('questions.index')->with('success', $message);
    }

    private function setEnv($key, $value)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . env($key),
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
        \Artisan::call('config:clear');
    }

}
