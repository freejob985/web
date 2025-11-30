<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['addresses']);
        return view('admin.users.show', compact('user'));
    }

    public function admins()
    {
        $admins = Admin::latest()->paginate(20);
        return view('admin.users.admins', compact('admins'));
    }
}
