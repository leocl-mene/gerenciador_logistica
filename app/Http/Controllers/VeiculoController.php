<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VeiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $veiculos = Veiculo::latest()->get(); // Pega todos os veículos, os mais recentes primeiro
        return view('secretaria.veiculos.index', compact('veiculos')); // Envia a variável $veiculos para a view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('secretaria.veiculos.create'); // Mostra a view do formulário
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados
        $request->validate([
            'placa' => 'required|string|unique:veiculos|max:10',
            'tipo' => ['required', Rule::in(['moto', 'carro'])],
            'modelo' => 'required|string|max:100',
            'marca' => 'nullable|string|max:100',
            'ano' => 'nullable|integer|digits:4',
            // 'km_por_litro' foi removido da validação
        ]);

        // 2. Criação do novo veículo no banco de dados
        Veiculo::create($request->all());

        // 3. Redirecionamento de volta para a lista com uma mensagem de sucesso
        return redirect()->route('veiculos.index')
                            ->with('success', 'Veículo cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Veiculo $veiculo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Veiculo $veiculo)
    {
        // Retorna a view do formulário de edição, passando os dados do veículo
        return view('secretaria.veiculos.edit', compact('veiculo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Veiculo $veiculo)
    {
        // 1. Validação dos dados (semelhante ao store, mas a placa pode ser a mesma do veículo atual)
        $request->validate([
            'placa' => 'required|string|max:10|unique:veiculos,placa,' . $veiculo->id,
            'tipo' => ['required', Rule::in(['moto', 'carro'])],
            'modelo' => 'required|string|max:100',
            'marca' => 'nullable|string|max:100',
            'ano' => 'nullable|integer|digits:4',
            // 'km_por_litro' foi removido da validação
        ]);

        // 2. Atualiza os dados do veículo no banco
        $veiculo->update($request->all());

        // 3. Redireciona de volta para a lista com mensagem de sucesso
        return redirect()->route('veiculos.index')
                            ->with('success', 'Veículo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Veiculo $veiculo)
    {
        // 1. Remove todas as associações Many-to-Many (motoboys)
        // Isso remove as linhas na tabela motoboy_veiculos que referenciam este veículo,
        // corrigindo o erro de Foreign Key.
        $veiculo->motoboys()->detach();

        // 2. Apaga o veículo do banco de dados (agora a exclusão é permitida)
        $veiculo->delete();

        // Redireciona de volta para a lista com mensagem de sucesso
        return redirect()->route('veiculos.index')
                            ->with('success', 'Veículo excluído com sucesso!');
    }
}