<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * ProfileController — "Lihat data diri" & "Ubah data diri" use cases
 * (tersedia untuk Admin, Guru, dan Siswa)
 */
class ProfileController extends Controller
{
    public function show(): View
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user) {
            $user->loadMissing(['sekolah', 'teachingClassRooms']);
        }

        return view('profile.show', compact('user'));
    }

    public function edit(): View
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $isAdmin = $user->isAdmin();

        $rules = ['name' => ['required', 'string', 'max:255']];

        if ($isAdmin) {
            $rules['email'] = ['required', 'email', 'unique:pengguna,email,'.$user->id];
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];

        if ($isAdmin) {
            $user->email = $validated['email'];

            if (! empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
        }

        $user->save();

        return redirect()->route('profil.show')->with('success', 'Profil berhasil diperbarui!');
    }
}
