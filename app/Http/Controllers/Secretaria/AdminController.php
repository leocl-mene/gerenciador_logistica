<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    /**
     * Display a listing of administrators.
     */
    public function index()
    {
        $administradores = User::where('cargo_id', User::ROLE_ADMIN)->latest()->get();

        return view('secretaria.administradores.index', compact('administradores'));
    }

    /**
     * Show the form for creating a new administrator.
     */
    public function create()
    {
        return view('secretaria.administradores.create');
    }

    /**
     * Store a newly created administrator in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'telefone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'password' => Hash::make($request->password),
            'cargo_id' => User::ROLE_ADMIN,
        ]);

        return redirect()->route('administradores.index')
            ->with('success', 'Administrador cadastrado com sucesso.');
    }

    /**
     * Show the form for editing the specified administrator.
     */
    public function edit(User $administrador)
    {
        return view('secretaria.administradores.edit', compact('administrador'));
    }

    /**
     * Update the specified administrator in storage.
     */
    public function update(Request $request, User $administrador)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$administrador->id],
            'telefone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = $request->only('name', 'email', 'telefone');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $administrador->update($data);

        return redirect()->route('administradores.index')
            ->with('success', 'Administrador atualizado com sucesso.');
    }

    /**
     * Remove the specified administrator from storage.
     */
    public function destroy(User $administrador)
    {
        if ($administrador->id === auth()->id() || $administrador->cargo_id != User::ROLE_ADMIN) {
            return back()->with('error', 'Acao nao permitida.');
        }

        $administrador->delete();

        return redirect()->route('administradores.index')
            ->with('success', 'Administrador excluido com sucesso.');
    }
}
