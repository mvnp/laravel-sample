<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\EstabelecimentoSearchService;

class EstadosController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $estados = Estado::query();
        $filteredData = array_filter($request->all());
        
        if(count($filteredData) === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No filter was entered in the search.'
            ]);    
        }
        
        $estados = EstabelecimentoSearchService::search($estados, $request);
        $estados = $estados->get();

        return response()->json([
            'status' => 'success',
            'count' => $estados->count(),
            'data' => $estados,
            'message' => 'Estados retrieved successfully'
        ]);
    }
}