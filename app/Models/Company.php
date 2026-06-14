<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name'])]

class Company extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }
}
