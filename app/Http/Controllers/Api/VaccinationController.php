<?php

namespace App\Http\Controllers\Api;

use App\Models\Society;
use App\Models\Vaccination;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VaccinationController extends Controller
{
    public function store(Request $request)
    {
        $society = Society::where('login_tokens', $request->token)->first();
        $consultation = Consultation::where('society_id', $society->id)->first();
        dd($consultation);

        if($consultation || $consultation->status !== 'accepted') {
            return response()->json([
                'message' => 'Your consultation must be accepted by doctor before',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'spot_id' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'error' => $validator->errors(),
            ], 401);
        }

        $vaccination = Vaccination::where('society_id', $society->id)->first();

        if($vaccination) {
            $now = Carbon::parse(date('Y-m-d'));
            $date = Carbon::parse(date($vaccination->date));
            $diff = $now->diffInDays($date);

            if($diff < 30) {
                return response()->json([
                    'message' => 'Wait at least +30 days from 1st Vaccination'
                ], 401);
            }
        }

        $vaccination_count = Vaccination::where('society_id', $society->id)->count();
        if($vaccination_count >= 2) {
            return response()->json([
                'message' => 'Society has been 2x vaccinated'
            ], 401);
        }

        $vaccination = Vaccination::create([
            'dose' => $vaccination ? 2 : 1,
            'date' => $request->date,
            'society_id' => $society->id,
            'spot_id' => $request->spot_id,
        ]);

        $message = ($vaccination->dose == 2 ? 'Second' : 'First') . ' vaccination registered successful';

        return response()->json([
            'message' => $message,
        ], 200);
    }
}
