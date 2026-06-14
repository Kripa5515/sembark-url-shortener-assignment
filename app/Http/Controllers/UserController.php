<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $getAllUsers = User::whereNot('id', auth()->id())->where('role_id',2)->get();
        return view('users.index', compact('getAllUsers'));
    }
}
