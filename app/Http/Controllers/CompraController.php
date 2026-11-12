<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function index() {
        $vendas = Venda::where('cliente_id', Auth::id())
                        ->with('produtos')
                        ->get();
        
        return view('compras.index', compact('vendas'));
    }
        

    public function create()
    {
        $produtos = Produto::orderBy('nome')->get();

        return view('compras.create', compact('produtos'));
    }

    
    public function store(Request $request) {
        // Filtra apenas itens com quantidade > 0
        $itens = array_filter($request->input('itens', []), fn($qtd) => $qtd > 0);
    
        // Bônus: Verifica estoque antes de criar a venda
        foreach ($itens as $produtoId => $qtd) {
            $produto = Produto::findOrFail($produtoId);
            if ($qtd > $produto->estoque) {
                return back()->withErrors(['estoque' => "Estoque insuficiente para {$produto->nome}."]);
            }
        }
    
        
        $venda = Venda::create(['cliente_id' => Auth::id()]);
    
        
        foreach ($itens as $produtoId => $qtd) {
            $produto = Produto::findOrFail($produtoId);
            $venda->produtos()->attach($produtoId, [
                'quantidade' => $qtd,
                'preco_unitario' => $produto->preco,
            ]);
            $produto->decrement('estoque', $qtd); 
        }
    
        return redirect()->route('compras.index')->with('success', 'Compra realizada!');
    }
    
}
