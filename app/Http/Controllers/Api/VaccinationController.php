<?php

namespace App\Http\Controllers\Api;

use App\Models\Vaccination;
use App\Models\Consultation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VaccinationController extends Controller
{
    public function store()
    {
        $society = Society::where('login_tokens', $request->token)->first();
        $consultation = Consultation::where('society_id', $society->id)->first();

        if($consultation->status !== 'accepted') {
            return response()->json([
                'message' => 'Your consultation must be accepted by doctor before',
            ], 401);
        }

        $vaccination = Vaccination::where('society_id', $society->id)->first();        

        $vaccination = Vaccination::create([
            'dose' => $vaccination ? 2 : 1,
            'date' => $request->date,
            'society_id' => $society->id,
            'spot_id' => $request->spot_id,
        ]);

        $message = ($vaccination->dose === 2 ? 'Second' : 'First') . ' vaccination registered successful';

        return response()->json([
            'message' => $message,
        ], 200);
    }
}
