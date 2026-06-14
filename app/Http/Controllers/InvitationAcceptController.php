<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class InvitationAcceptController extends Controller
{
    public function show(string $token)
    {
        $invitation = Invitation::where('token', $token )->where('expires_at', '>', now() )->firstOrFail();
        return view('invitations.accept', compact('invitation') );
    }

    public function store(Request $request, string $token )
    {
        $invitation = Invitation::where('token', $token)->where('expires_at', '>', now() )->firstOrFail();
        $request->validate([
            'name' => ['required','string','max:255'],
            'password' => ['required','min:8']
        ]); 

        $role = Role::where('name',$invitation->role)->first();

        User::create([
            'company_id' =>$invitation->company_id,
            'role_id' =>$role->id,
            'name' =>$request->name,
            'email' =>$invitation->email,
            'password' =>Hash::make($request->password)
        ]);

        $invitation->delete();
        return redirect('/login')->with('success','Account created successfully');
    }
}
