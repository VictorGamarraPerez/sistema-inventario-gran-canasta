<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar login y enviar código 2FA
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas son incorrectas.',
            ])->withInput();
        }

        // Generar código de verificación de 6 dígitos
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Eliminar códigos anteriores no utilizados
        VerificationCode::where('user_id', $user->id)
            ->where('verified', false)
            ->delete();

        // Crear nuevo código
        VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
            'verified' => false,
        ]);

        // Enviar código por correo
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name));
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Error al enviar el código de verificación. Por favor, intente nuevamente.',
            ])->withInput();
        }

        // Guardar el user_id en sesión para la verificación
        session(['verification_user_id' => $user->id]);

        return redirect()->route('verification.form')
            ->with('success', 'Se ha enviado un código de verificación a su correo electrónico.');
    }

    // Mostrar formulario de verificación
    public function showVerificationForm()
    {
        if (!session('verification_user_id')) {
            return redirect()->route('login')->withErrors(['error' => 'Sesión expirada.']);
        }

        return view('auth.verification');
    }

    // Verificar código 2FA
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session('verification_user_id');

        if (!$userId) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Sesión expirada. Por favor, inicie sesión nuevamente.']);
        }

        $verificationCode = VerificationCode::where('user_id', $userId)
            ->where('code', $request->code)
            ->where('verified', false)
            ->first();

        if (!$verificationCode) {
            return back()->withErrors([
                'code' => 'El código de verificación es incorrecto.',
            ]);
        }

        if ($verificationCode->isExpired()) {
            return back()->withErrors([
                'code' => 'El código de verificación ha expirado.',
            ]);
        }

        // Marcar código como verificado
        $verificationCode->update(['verified' => true]);

        // Autenticar al usuario
        Auth::loginUsingId($userId);

        // Limpiar sesión de verificación
        session()->forget('verification_user_id');

        return redirect()->intended('/dashboard')
            ->with('success', '¡Bienvenido al sistema de La Gran Canasta!');
    }

    // Reenviar código de verificación
    public function resendCode()
    {
        $userId = session('verification_user_id');

        if (!$userId) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Sesión expirada. Por favor, inicie sesión nuevamente.']);
        }

        $user = User::find($userId);

        // Generar nuevo código
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Eliminar códigos anteriores
        VerificationCode::where('user_id', $user->id)
            ->where('verified', false)
            ->delete();

        // Crear nuevo código
        VerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
            'verified' => false,
        ]);

        // Enviar código por correo
        try {
            Mail::to($user->email)->send(new VerificationCodeMail($code, $user->name));
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Error al enviar el código de verificación. Por favor, intente nuevamente.',
            ]);
        }

        return back()->with('success', 'Se ha enviado un nuevo código de verificación a su correo electrónico.');
    }

    // Cerrar sesión
    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente.');
    }
}
