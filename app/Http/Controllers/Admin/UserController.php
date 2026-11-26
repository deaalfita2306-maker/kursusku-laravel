<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $role = trim((string) $request->query('role', ''));

        $query = User::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (in_array($role, ['admin', 'user'], true)) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'role'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,user'],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,user'],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
        ];

        // Cegah admin menonaktifkan atau menurunkan role dirinya sendiri
        if (Auth::id() === $user->id) {
            $data['role'] = 'admin';
            $data['is_active'] = true;
        }

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
