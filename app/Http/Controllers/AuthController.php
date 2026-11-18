<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

            \App\Helpers\AuditoriaHelper::registrar(
                'Autenticación',
                'Login',
                "Inicio de sesión exitoso del usuario '" . auth()->user()->name . "' (" . auth()->user()->email . ")"
            );

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
        if (Auth::check()) {
            \App\Helpers\AuditoriaHelper::registrar(
                'Autenticación',
                'Logout',
                "Cierre de sesión del usuario '" . Auth::user()->name . "'"
            );
        }

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
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'La contraseña actual no es correcta.',
            ]);
        }

        $user->password = $request->password;
        $user->must_change_password = false;
        $user->save();

        \App\Helpers\AuditoriaHelper::registrar(
            'Autenticación',
            'Edición',
            "Cambio de contraseña exitoso del usuario '{$user->name}'"
        );

        return redirect()->route('home')->with('success', 'Contraseña cambiada correctamente.');
    }
}
