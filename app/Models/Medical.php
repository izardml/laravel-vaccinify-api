<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
