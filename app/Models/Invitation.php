<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([ 'company_id', 'email', 'role', 'token', 'expires_at', 'created_by' ])]

class Invitation extends Model
{
    public function company()
    {
        return $this->belongsTo(
            Company::class
        );
    }
}
