<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SystemRequirement;

class ProductRequirementController extends Controller
{
    public function edit(Product $product)
    {
        // Busca os requisitos existentes para já preencher o formulário
        $min = $product->systemRequirements()->where('type', 'minimum')->firstOrNew();
        $rec = $product->systemRequirements()->where('type', 'recommended')->firstOrNew();

        return view('products.requirements-form', [
            'product' => $product,
            'min' => $min,
            'rec' => $rec,
        ]);
    }

    /**
     * Salva os requisitos do produto no banco de dados.
     */
    public function store(Request $request, Product $product)
    {
        // 1. Validação (simples, pode ser melhorada)
        $data = $request->validate([
            'min_os' => 'required|string|max:255',
            'min_processor' => 'required|string|max:255',
            'min_memory' => 'required|string|max:255',
            'min_graphics' => 'required|string|max:255',
            'min_storage' => 'required|string|max:255',

            'rec_os' => 'nullable|string|max:255',
            'rec_processor' => 'nullable|string|max:255',
            'rec_memory' => 'nullable|string|max:255',
            'rec_graphics' => 'nullable|string|max:255',
            'rec_storage' => 'nullable|string|max:255',
        ]);

        // 2. Lógica de Criação/Atualização (A Mágica)
        // Usamos updateOrCreate para criar ou atualizar caso já exista.

        // Cria/Atualiza os requisitos MÍNIMOS
        SystemRequirement::updateOrCreate(
            [
                'product_id' => $product->id,
                'type' => 'minimum'
            ],
            [
                'os' => $data['min_os'],
                'processor' => $data['min_processor'],
                'memory' => $data['min_memory'],
                'graphics' => $data['min_graphics'],
                'storage' => $data['min_storage'],
            ]
        );

        // Cria/Atualiza os requisitos RECOMENDADOS (apenas se o usuário preencheu algo)
        if ($request->filled('rec_os')) {
            SystemRequirement::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'type' => 'recommended'
                ],
                [
                    'os' => $data['rec_os'] ?? null,
                    'processor' => $data['rec_processor'] ?? null,
                    'memory' => $data['rec_memory'] ?? null,
                    'graphics' => $data['rec_graphics'] ?? null,
                    'storage' => $data['rec_storage'] ?? null,
                ]
            );
        }

        // 3. Redireciona de volta
        return redirect()->back()->with('success', 'Requisitos salvos com sucesso!');
    }
}
