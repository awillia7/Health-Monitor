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
        $users = User::whereNotNull('roles')->orderBy('name', 'asc')->get();

        return view('users.index', compact('users'));
    }
}
