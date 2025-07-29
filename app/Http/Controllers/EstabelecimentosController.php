<?php

namespace App\Http\Controllers;

use App\Models\Estabelecimento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\EstabelecimentoSearchService;

class EstabelecimentosController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $estabelecimentos = Estabelecimento::query()
            ->where('nome_fantasia', '<>', '')
            ->with(['empresa', 'socio'])
            ->whereHas('empresa')
            ->whereHas('socio');

        $filteredData = array_filter($request->all());
        
        if(count($filteredData) === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No filter was entered in the search.'
            ]);    
        }
        
        $estabelecimentos = EstabelecimentoSearchService::search($estabelecimentos, $request);
        $estabelecimentos = $estabelecimentos->get();

        return response()->json([
            'status' => 'success',
            'count' => $estabelecimentos->count(),
            'data' => $estabelecimentos,
            'message' => 'Estabelecimentos retrieved successfully'
        ]);
    }
}