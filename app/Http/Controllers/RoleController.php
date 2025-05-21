<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::paginate(7);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoleRequest $request)
    {
        Role::create([
            'name' => $request->name,
            'permissions' => $request->permissions,
        ]);
        return redirect()->route('roles.index')->with('success', 'Role Added Seccussfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        $role = Role::findOrFail($id);
        // return $request->permissions;
        $role->update([
            'name'  => $request->name,
            'permissions' => $request->permissions,
            'updated_at' =>now(),
        ]);
        // $role->save();
        return redirect()->route('roles.index')->with('success', 'Role Updated Seccussfuly !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Role::destroy($id);
        return redirect()->route('roles.index')->with('success', 'Role Updated Seccussfuly !');
    }
    // New method for removing a role
    public function removeRole(User $user, Role $role)
    {
        // Optional: Check authorization
        // if (Gate::allows('manage-users')) {
            if ($user->roles()->detach($role->id)) {
                return redirect()->route('users')->with('success', 'Role removed successfully.');
            }
            return redirect()->route('users')->with('error', 'Failed to remove role.');
        // }
        // return redirect()->route('users.index')->with('error', 'Unauthorized action.');
    }
}
