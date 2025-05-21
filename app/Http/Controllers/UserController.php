<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeRoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function users()
    {
        $users =  User::paginate(5);
        $roles = Role::all();
        return view('auth.users', compact('users', 'roles'));
    }
    public function changeRole(ChangeRoleRequest $request, User $user)
    {
        $user = User::find($request->user_id);
        $user->roles()->attach($request->role_id);
        return redirect()->back()->with('success', 'Roles Changed Successfully!');
    }

}
