<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasswordSetController extends Controller
{
    public function showSetPasswordForm($token)
    {
        return view('auth.set-password')->with(['token' => $token]);
    }

    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $user = User::where('set_password_token', $request->input('token'))->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Invalid token.');
        }
        $user->update([
            'password' => Hash::make($request->input('password')),
            'set_password_token' => null,
        ]);
        return redirect()->route('login')->with('success', 'Password set successfully. You can now login.');
    }
}
