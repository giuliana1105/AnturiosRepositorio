<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');

        // Permitir login con email y nro_identificacion como password
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (auth()->user()->must_change_password) {
                return redirect()->route('password.change.form');
            }
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'El correo o la contraseña no son correctos.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->password = $request->password;
        $user->must_change_password = false;
        $user->save();

        return redirect()->route('home')->with('success', 'Contraseña cambiada correctamente.');
    }
}
