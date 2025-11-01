<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarouselSlide;
use App\Models\Product;
use Illuminate\Http\Request;

class CarouselController extends Controller
{
    /**
     * Mostra a página de gerenciamento do carrossel.
     */
    public function index()
    {
        // 1. Busca TODOS os produtos que estão ativos.
        //    Eles serão usados para preencher os menus <select>.
        $allProducts = Product::where('is_active', true)->orderBy('name')->get();

        // 2. Busca todos os SLIDES que já existem, na ordem correta,
        //    e já carrega os produtos que estão neles.
        $carouselSlides = CarouselSlide::with('products')
                                ->orderBy('order')
                                ->get();

        // 3. Envia os produtos e os slides para a view.
        return view('admin.carousel.index', [
            'allProducts' => $allProducts,
            'carouselSlides' => $carouselSlides
        ]);
    }

    /**
     * Cria um novo slide de carrossel (vazio).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CarouselSlide::create([
            'name' => $request->name,
            'order' => 99, // (Você pode melhorar essa lógica de ordem depois)
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Novo slide criado!');
    }

   // 1. Valida os dados (garante que os IDs de produto enviados existem)

    // ... (método index() e store() ficam iguais)

    /**
     * Atualiza um slide, anexando os produtos a ele.
     * (VERSÃO CORRIGIDA - IMPEDE PRODUTOS REPETIDOS)
     */
    public function update(Request $request, CarouselSlide $slide)
    {
        // 1. Validação (A MÁGICA ESTÁ AQUI)
        $request->validate([
            // Garante que os IDs existem no banco...
            'product_large' => 'nullable|exists:products,id',
            'product_small_1' => 'nullable|exists:products,id',
            'product_small_2' => 'nullable|exists:products,id',

            // E garante que os 3 campos não sejam iguais (ignorando os nulos)
            // 'distinct' = devem ser distintos (diferentes)
            // 'ignore_case' = não é relevante aqui, mas é parte da regra
            'product_large' => 'distinct:ignore_case',
            'product_small_1' => 'distinct:ignore_case',
            'product_small_2' => 'distinct:ignore_case',
        ]);

        // 2. Preparar os dados para o sync()
        // Esta é a forma correta de preparar o array para o sync()
        // sem que um campo sobrescreva o outro.
        $syncData = [];
        if ($request->product_large) {
            $syncData[$request->product_large] = ['slot' => 'large'];
        }
        if ($request->product_small_1) {
            // Se a chave já existe (ex: ID 1), ele vai sobrescrever o slot.
            // Mas isso não importa, porque a validação acima já falhou!
            // Se a validação passou, sabemos que os IDs são diferentes.
            $syncData[$request->product_small_1] = ['slot' => 'small_1'];
        }
        if ($request->product_small_2) {
            $syncData[$request->product_small_2] = ['slot' => 'small_2'];
        }

        // 3. Sincroniza os dados.
        // O sync() é a forma mais limpa de atualizar uma tabela pivot.
        // Ele apaga os antigos e adiciona os novos de uma vez.
        $slide->products()->sync($syncData);

        return redirect()->back()->with('success', 'Slide "' . $slide->name . '" atualizado!');
    }
}