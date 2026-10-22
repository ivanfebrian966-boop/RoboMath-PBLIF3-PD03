<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $roleFilter = $request->query('role', 'all');
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('search');

        $query = User::whereNot('role', 'admin')->orderBy('role')->orderBy('name');

        if ($roleFilter !== 'all') {
            $query->where('role', $roleFilter);
        }

        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();

        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalInactive = User::where('is_active', false)->count();

        return view('admin.users.index', compact('users', 'roleFilter', 'statusFilter', 'search', 'totalSiswa', 'totalGuru', 'totalInactive'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:pengguna,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:siswa,guru,admin'],
            'kelas' => ['nullable', 'required_if:role,siswa', 'integer', 'min:1', 'max:6'],
        ]);

        $validated['is_active'] = true;
        $validated['level'] = 1;
        $validated['total_score'] = 0;

        $user = User::create($validated);

        return back()->with('success', "Akun \"{$user->name}\" berhasil ditambahkan. ✅");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('pengguna')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:siswa,guru,admin'],
            'kelas' => ['nullable', 'required_if:role,siswa', 'integer', 'min:1', 'max:6'],
            'is_active' => ['nullable'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        if ($validated['role'] !== 'siswa') {
            $validated['kelas'] = null;
        }

        $user->update($validated);

        return back()->with('success', "Data akun \"{$user->name}\" berhasil diperbarui. ✅");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Akun \"{$name}\" berhasil dihapus. 🗑️");
    }

    public function toggle(User $user): RedirectResponse
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['error' => 'Akun admin tidak dapat dinonaktifkan.']);
        }

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun \"{$user->name}\" berhasil {$status}. ✅");
    }
}
