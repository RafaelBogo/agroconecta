<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show()
    {
        // Pega os dados do usuário autenticado
        $user = Auth::user();

        // Retorna a view com os dados do usuário
        return view('account.myData', compact('user'));
    }
    public function update(Request $request)
    {
        // Validação dos campos enviados
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
        ]);

        // Obter o usuário autenticado
        $user = Auth::user();

        // Atualizar os dados do usuário
        $user->update($request->only(['name', 'phone', 'address']));

        // Redirecionar de volta com uma mensagem de sucesso
        return redirect()->route('user.data')->with('success', 'Seus dados foram atualizados com sucesso!');
    }
}
