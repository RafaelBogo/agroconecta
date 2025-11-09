<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            return redirect()->route('dashboard')->with('message', 'Login realizado com sucesso!');
        }

        return back()->withErrors(['email' => 'Credenciais inválidas.']);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Sessão encerrada com sucesso.');
    }


    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'password' => 'required|min:6|confirmed',
        ]);

        $verificationCode = Str::random(6);

        Session::put('temp_user', [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'verification_code' => $verificationCode,
        ]);

        Mail::raw("Seu código de verificação é: $verificationCode", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Código de Verificação - AgroConecta');
        });

        return redirect()->route('verify')->with('message', 'Código de verificação enviado para o e-mail.');
    }

    public function showVerificationForm()
    {
        return view('auth.verify');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        $tempUser = Session::get('temp_user');

        if ($tempUser && $tempUser['verification_code'] === $request->verification_code) {
            User::create([
                'name' => $tempUser['name'],
                'email' => $tempUser['email'],
                'password' => $tempUser['password'],
                'phone' => $tempUser['phone'],
                'address' => $tempUser['address'],

            ]);

            Session::forget('temp_user');

            return redirect()->route('login')->with('message', 'Conta criada com sucesso!');
        }

        return back()->withErrors(['verification_code' => 'Código inválido.']);
    }
}
