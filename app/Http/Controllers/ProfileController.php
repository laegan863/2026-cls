<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user()->load('role');
        return view('files.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = Auth::user()->load('role');
        return view('files.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'contact_no' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the form for changing password.
     */
    public function showChangePassword()
    {
        $user = Auth::user();
        return view('files.profile.change-password', compact('user'));
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Password changed successfully.');
    }
}
