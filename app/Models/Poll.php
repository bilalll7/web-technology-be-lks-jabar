<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function choice(){
        return $this->hasMany(Choice::class);
    }
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
