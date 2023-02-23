<?php

namespace App\Http\Controllers\Api;

use App\Models\Spot;
use App\Models\Society;
use App\Models\SpotVacine;
use App\Models\Vaccination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpotController extends Controller
{
    public function index(Request $request)
    {
        $society = Society::where('login_tokens', $request->token)->first();

        $spots = Spot::where('regional_id', $society->regional_id)->get();

        return response()->json([
            'spots' => $spots,
        ], 200);
    }

    public function show(Request $request, Spot $spot)
    {
        if(!$request->date) {
            $request->date = date('Y-m-d');
        }

        $date = date('M d, Y', strtotime($request->date));
        $spot->makeHidden('available_vaccines');
        $vaccinations_count = Vaccination::where([
            'date' => $request->date,
            'spot_id' => $spot->id,
        ])->count();

        return response()->json([
            'date' => $date,
            'spot' => $spot,
            'vaccinations_count' => $vaccinations_count,
        ], 200);
    }
}
