<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return view('account.myData', compact('user'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
        ]);

        $user = Auth::user();

        $user->update($request->only(['name', 'phone', 'address','city']));

        return redirect()->route('user.data')->with('success', 'Seus dados foram atualizados com sucesso!');
    }
}
