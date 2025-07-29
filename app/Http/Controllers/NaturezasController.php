<?php

namespace App\Http\Controllers;

use App\Models\Natureza;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\EstabelecimentoSearchService;

class NaturezasController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $natureza = Natureza::query();
        $filteredData = array_filter($request->all());
        
        if(count($filteredData) === 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'No filter was entered in the search.'
            ]);    
        }
        
        $natureza = EstabelecimentoSearchService::search($natureza, $request);
        $natureza = $natureza->get();

        return response()->json([
            'status' => 'success',
            'count' => $natureza->count(),
            'data' => $natureza,
            'message' => 'Naturezas retrieved successfully'
        ]);
    }
}