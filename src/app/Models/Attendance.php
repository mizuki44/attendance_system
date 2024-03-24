<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Rest;
use App\Models\User;

class Attendance extends Model
{
    use HasFactory;
    
    public function user()
    {
        return $this->hasMany('App\Models\User');
    }
    
    public function rests()
    {
        return $this->hasMany('App\Models\Rest');
    }

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}