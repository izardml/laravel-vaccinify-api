<?php

namespace App\Models;

use App\Models\Vaccine;
use App\Models\SpotVaccine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spot extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['regional_id'];
    protected $appends = ['available_vaccines'];
    
    public function regional()
    {
        return $this->belongsTo(Regional::class);
    }

    public function getAvailableVaccinesAttribute()
    {
        $available_vaccines = [];
        foreach(Vaccine::all() as $vaccine) {
            $available_vaccines[$vaccine->name] = SpotVaccine::where('spot_id', $this->id)->where('vaccine_id', $vaccine->id)->first() ? true : false;
        }
        return $available_vaccines;
    }
}
