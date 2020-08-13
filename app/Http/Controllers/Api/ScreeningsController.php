<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Screening;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\ScreeningResource;

class ScreeningsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $screenings = Screening::with(['user'])
        ->whereDate('created_at', Carbon::today());

        // Check minutes parameter
        if (request()->minutes) {
            $screenings->where('created_at', '>=', Carbon::now()->subMinutes(request()->minutes));
        }
        $screenings = $screenings->get();

        // Check status parameter
        if (request()->status) {
            $screenings = $screenings->filter(function ($screening) {
                return strtolower($screening->status) === strtolower(request()->status);
            });
        }

        return ScreeningResource::collection($screenings)->additional([
            'total' => $screenings->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Screening  $screening
     * @return \Illuminate\Http\Response
     */
    public function show(Screening $screening)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Screening  $screening
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Screening $screening)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Screening  $screening
     * @return \Illuminate\Http\Response
     */
    public function destroy(Screening $screening)
    {
        //
    }
}
