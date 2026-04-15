<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('nama')->get();
        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'nama' => 'required|string|max:100',
            'role' => 'required|in:admin,guru',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        User::create([
            'username' => $request->username,
            'password' => $request->password,
            'nama' => $request->nama,
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'nama' => 'required|string|max:100',
            'role' => 'required|in:admin,guru',
        ];

        if ($request->has('password') && $request->password) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['nama', 'role']);
        
        if ($request->has('password') && $request->password) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('user.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('user.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dihapus');
    }
}
