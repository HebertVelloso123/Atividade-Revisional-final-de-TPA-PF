<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::with('vendedor')->get();
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(StoreProdutoRequest $request) {
        $dados = $request->validated();
        $dados['vendedor_id'] = Auth::id(); // Requisito R6
    
        Produto::create($dados);
        return redirect()->route('produtos.index');
    }
    
}
