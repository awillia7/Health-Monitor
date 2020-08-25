<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ADMIN');
    }

    public function index()
    {
        $users = User::whereNotNull('roles')->where('username', '!=', getenv('ADMIN_ACCOUNT'))->orderBy('name', 'asc')->get();

        return view('users.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize("update", $user);

        $roles = [];
        ($request->admin) ? array_push($roles, 'ADMIN') : null;
        ($request->override) ? array_push($roles, 'OVERRIDE') : null;
        ($request->delete) ? array_push($roles, 'DELETE') : null;
        ($request->manager) ? array_push($roles, 'MANAGER') : null;
        
        $user->roles = empty($roles) ? null : $roles;
        $user->save();

        $s_message = "{$user->name} has been updated.";
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $s_message
            ]);
        }

        return redirect()->route('users.index')->with('success', $s_message);
    }
}
