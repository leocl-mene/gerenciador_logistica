<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Veiculo; // Import adicionado
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class MotoboyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Busca todos os usuários onde o cargo_id é 3 (Motoboy)
        $motoboys = User::where('cargo_id', 3)->latest()->get();

        return view('secretaria.motoboys.index', compact('motoboys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('secretaria.motoboys.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'telefone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Criação do novo usuário (motoboy)
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'password' => Hash::make($request->password),
            'cargo_id' => 3, // Hardcoded para Motoboy
        ]);

        return redirect()->route('motoboys.index')
                         ->with('success', 'Motoboy cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $motoboy)
    {
        return view('secretaria.motoboys.edit', compact('motoboy'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $motoboy)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$motoboy->id],
            'telefone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // Prepara os dados para atualização
        $data = $request->only('name', 'email', 'telefone');

        // Só atualiza a senha se uma nova for fornecida
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $motoboy->update($data);

        return redirect()->route('motoboys.index')
                         ->with('success', 'Motoboy atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $motoboy)
    {
        // Medida de segurança para não se auto-excluir ou excluir outros admins
        if ($motoboy->id === auth()->id() || $motoboy->cargo_id != 3) {
            return back()->with('error', 'Ação não permitida.');
        }

        $motoboy->delete();

        return redirect()->route('motoboys.index')
                         ->with('success', 'Motoboy excluído com sucesso.');
    }

    /**
     * Mostra a página para gerenciar os veículos de um motoboy.
     */
    public function gerenciarVeiculos(User $motoboy)
    {
        $todosVeiculos = Veiculo::orderBy('modelo')->get();
        // Pega os IDs dos veículos que já estão associados a este motoboy
        $veiculosAtuais = $motoboy->veiculos->pluck('id')->toArray();

        return view('secretaria.motoboys.gerenciar-veiculos', compact('motoboy', 'todosVeiculos', 'veiculosAtuais'));
    }

    /**
     * Salva as associações de veículos para um motoboy.
     */
    public function salvarVeiculos(Request $request, User $motoboy)
    {
        // sync() é o método perfeito do Laravel para isso.
        // Ele adiciona as novas associações, remove as que não foram marcadas
        // e mantém as que já existiam, tudo em um único comando.
        $motoboy->veiculos()->sync($request->veiculos_ids ?? []);

        return redirect()->route('motoboys.index')->with('success', 'Veículos associados ao motoboy com sucesso!');
    }
}