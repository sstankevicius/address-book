<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function path()
    {
        return "/contacts/{$this->id}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sharing()
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['user_id']);
    }


}
