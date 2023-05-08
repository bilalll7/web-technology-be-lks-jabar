<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function choice(){
        return $this->belongsTo(Choice::class, 'poll_id');
    }
    public function division(){
        return $this->belongsTo(Division::class, 'division_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
