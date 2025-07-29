<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\{Municipio, Estado, Qualificacao, Natureza, Motivo, Pais, Cnae, Estabelecimento};
use App\Services\EstabelecimentoSearchService;

class HomeController extends Controller
{
    /**
     * Display the search form
     */
    public function index()
    {
        // Load all reference data for dropdowns
        $estados = Estado::orderBy('sigla')->get();
        $qualificacoes = Qualificacao::orderBy('descricao')->get();
        $naturezas = Natureza::orderBy('descricao')->get();
        $motivos = Motivo::orderBy('descricao')->get();
        $paises = Pais::orderBy('descricao')->get();
        $cnaes = Cnae::orderBy('descricao')->get();

        return view('home', compact(
            'estados',
            'qualificacoes', 
            'naturezas',
            'motivos',
            'paises',
            'cnaes'
        ));
    }

    /**
     * Handle the search request and return results
     */
    public function search(Request $request)
    {
        try {
            // Start with base query
            $estabelecimentos = Estabelecimento::with(['empresa', 'municipio', 'estado']);
            
            // Apply search filters using the service
            $estabelecimentos = EstabelecimentoSearchService::search($estabelecimentos, $request);
            
            // Get results
            $results = $estabelecimentos->get();
            
            // Load reference data for the form (in case of validation errors)
            $estados = Estado::orderBy('nome')->get();
            $municipios = collect();
            
            // Load municipios if state is selected
            if ($request->filled('uf')) {
                $municipios = Municipio::where('uf', $request->uf)
                    ->orderBy('nome')
                    ->get();
            }
            
            $qualificacoes = Qualificacao::orderBy('descricao')->get();
            $naturezas = Natureza::orderBy('descricao')->get();
            $motivos = Motivo::orderBy('descricao')->get();
            $paises = Pais::orderBy('nome')->get();
            $cnaes = Cnae::orderBy('descricao')->get();

            return view('home', compact(
                'results',
                'estados',
                'municipios',
                'qualificacoes',
                'naturezas', 
                'motivos',
                'paises',
                'cnaes'
            ))->with('searchData', $request->all());

        } catch (\Exception $e) {
            return back()->withErrors(['search' => 'Erro ao realizar busca: ' . $e->getMessage()]);
        }
    }

    /**
     * Get municipios by UF (AJAX endpoint)
     */
    public function getMunicipios(Request $request)
    {
        $uf = $request->get('uf');
        
        if (!$uf) {
            return response()->json([]);
        }

        $municipios = Municipio::where('uf', $uf)
            ->orderBy('nome')
            ->get(['codigo', 'nome']);

        return response()->json($municipios);
    }

    /**
     * Get CNAE suggestions (AJAX endpoint)
     */
    public function getCnaes(Request $request)
    {
        $search = $request->get('search');
        
        $cnaes = Cnae::when($search, function($query, $search) {
                return $query->where('descricao', 'LIKE', "%{$search}%")
                           ->orWhere('codigo', 'LIKE', "%{$search}%");
            })
            ->orderBy('codigo')
            ->limit(20)
            ->get(['codigo', 'descricao']);

        return response()->json($cnaes);
    }
}