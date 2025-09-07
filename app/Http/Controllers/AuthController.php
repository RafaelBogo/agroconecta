<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Exibir o formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Lógica de login (a ser implementada)
    public function login(Request $request)
{
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            // Autenticação bem-sucedida
            return redirect()->route('dashboard')->with('message', 'Login realizado com sucesso!');
        }

        // Autenticação falhou
        return back()->withErrors(['email' => 'Credenciais inválidas.']);
    }

    //Logout
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Sessão encerrada com sucesso.');
    }




    // Exibir o formulário de registro
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Processar o registro e enviar o código de verificação
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Gerar código de verificação
        $verificationCode = Str::random(6);

        // Armazenar os dados temporariamente na sessão
        Session::put('temp_user', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'verification_code' => $verificationCode,
        ]);

        // Enviar o código de verificação por e-mail
        Mail::raw("Seu código de verificação é: $verificationCode", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Código de Verificação - AgroConecta');
        });

        // Redirecionar para a view de verificação
        return redirect()->route('verify')->with('message', 'Código de verificação enviado para o e-mail.');
    }

    // Exibir o formulário de verificação
    public function showVerificationForm()
    {
        return view('auth.verify');
    }

    // Verificar o código de verificação
    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string',
        ]);

        // Obter os dados temporários do usuário
        $tempUser = Session::get('temp_user');

        // Verificar o código
        if ($tempUser && $tempUser['verification_code'] === $request->verification_code) {
            // Criar o usuário no banco de dados
            User::create([
                'name' => $tempUser['name'],
                'email' => $tempUser['email'],
                'password' => $tempUser['password'],
            ]);

            // Limpar os dados temporários da sessão
            Session::forget('temp_user');

            // Redirecionar com mensagem de sucesso
            return redirect()->route('login')->with('message', 'Conta criada com sucesso!');
        }

        return back()->withErrors(['verification_code' => 'Código inválido.']);
    }
}
