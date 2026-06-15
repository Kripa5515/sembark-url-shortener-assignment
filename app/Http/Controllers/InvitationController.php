<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Collection\Collection;

class InvitationController extends Controller
{
    
    // private function allowedRoles()
    // {
    //     $user = auth()->user();
    //     if ($user->isSuperAdmin()) {
    //         return ['Admin'];
    //     }
    //     return [];
    // }

    private function allowedRoles()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return ['Admin'];
        }

        if ($user->isAdmin()) {
            return ['Admin','Member'];
        }

        return [];
    }

    public function index()
    {
        $user = auth()->user();
        $isInvited = null;
        if ($user->isSuperAdmin()) {
            $invitations = Invitation::with('company')->latest()->get();
            
        } else {
            $isInvited = Invitation::where('created_for', $user->id)->first();
            $invitations = Invitation::where('company_id', $user->company_id)->latest()->get() ?? new Collection();
           
        }
        return view('invitations.index', compact('invitations', 'isInvited'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            $companies = Company::all();
            $emaildatas = User::where(function ($query) {
                $query->whereNull('company_id')
                      ->orWhere('company_id', '');
            })
            ->where('role_id', 2)
            ->get();
        } else {
            $companies = Company::where('id', $user->company_id)->get();
            $allowedRoleIds = Role::whereIn('name', $this->allowedRoles())->pluck('id')->toArray() ?? [];
            $emaildatas = User::whereIn('role_id', $allowedRoleIds)
            ->where(function ($query) use ($user) {
                $query->orWhere('company_id', '')->orWhereNull('company_id');
            })
            ->get();
        }

        return view('invitations.create', [
            'roles' => $this->allowedRoles(),
            'companies' => $companies,
            'emaildatas' => $emaildatas
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $allowedRoles = $this->allowedRoles();

        if ( ! in_array( $request->role, $allowedRoles ) ) {
            abort( 403, 'Role not allowed' );
        }
        $companyId = $user->isSuperAdmin() ? $request->company_id : $user->company_id;
        if (!$companyId) {
            return back()->withErrors(['company_id' => 'Company selection is required.']);
        }
           
        Invitation::create([
            'company_id' => $companyId,
            'email' => $request->email,
            'role' => $request->role,
            'created_by' => $user->id,
            'created_for' => getUserIdByEmail($request->email),
            'token' => Str::uuid(),
            'expires_at' => now()->addDays(7)
        ]);
        return redirect()->route('invitations.index')->with( 'success', 'Invitation sent' );
    }
}
