<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
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

        $users = $query->paginate(25)->withQueryString();

        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalInactive = User::where('is_active', false)->count();

        return view('admin.users.index', compact('users', 'roleFilter', 'statusFilter', 'search', 'totalSiswa', 'totalGuru', 'totalInactive'));
    }

    public function toggle(User $user)
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['error' => 'Akun admin tidak dapat dinonaktifkan.']);
        }

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun \"{$user->name}\" berhasil {$status}. ✅");
    }
}
