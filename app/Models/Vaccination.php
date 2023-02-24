<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = [
        'id',
        'date',
        'society_id',
        'spot_id',
        'vaccine_id',
        'doctor_id',
        'officer_id',
    ];
    protected $appends = [
        'vaccination_date',
        'status',
        'vaccinator'
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Medical::class, 'doctor_id');
    }

    public function officer()
    {
        return $this->belongsTo(Medical::class, 'officer_id');
    }

    public function getVaccinationDateAttribute()
    {
        return $this->date;
    }

    public function getStatusAttribute()
    {
        return $this->vaccine ? 'done' : 'registered';
    }

    public function getVaccinatorAttribute()
    {
        return $this->doctor;
    }
}
