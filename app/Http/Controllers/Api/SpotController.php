<?php

namespace App\Http\Controllers\Api;

use App\Models\Spot;
use App\Models\Society;
use App\Models\SpotVacine;
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
}
