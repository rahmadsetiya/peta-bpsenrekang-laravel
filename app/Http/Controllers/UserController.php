<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('User/Index', [
            'users' => User::orderBy('username')->get(['id', 'username', 'role', 'created_at']),
        ]);
    }

    public function create()
    {
        return Inertia::render('User/Form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3|unique:users,username',
            'password' => 'required|min:6',
            'role' => 'required|in:ADMIN,IPDS,SUBJECT_MATTER,GUEST',
        ]);

        User::create([
            'id' => Str::uuid(),
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return Inertia::render('User/Form', ['user' => $user->only('id', 'username', 'role')]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|min:3|unique:users,username,' . $user->id,
            'role' => 'required|in:ADMIN,IPDS,SUBJECT_MATTER,GUEST',
        ]);

        $data = ['username' => $request->username, 'role' => $request->role];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
