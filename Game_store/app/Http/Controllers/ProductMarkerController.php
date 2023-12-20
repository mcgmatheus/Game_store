<?php

namespace App\Http\Controllers;

use App\Models\ProductMarker;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductMarkerController extends Controller
{
    public function store(Request $request){
        //Cadastra um marcador em um jogo
        try {
            if (count(array_diff(array_keys($request->all()), ['product_id', 'marker_id'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'product_id'=>'integer|required',
                'marker_id'=>'integer|required|unique:products_markers'
            ]);
            $searchProductId = $request->input('product_id');
            $searchMarkerId = $request->input('marker_id');
            if (!(DB::table('products')->where('id', $searchProductId)->count() > 0)){
                return response()->json(['message' => 'ID de produto não encontrado'], 400);
            }
            if (!(DB::table('markers')->where('id', $searchMarkerId)->count() > 0)){
                return response()->json(['message' => 'ID de marcador não encontrado'], 400);
            }
            $data = $request->all();
            $productMarker = ProductMarker::create($data);
            return $productMarker;
        } catch (Exception $exception) {
            return response()->json(['message'=> $exception->getMessage()],400);
        }
    }
    public function index(Request $request){
        //Lista todos os marcadores de um jogo
        $product = $request->query('product_id');
        $searchProductId = $request->input('product_id');
        if (!(DB::table('products')->where('id', $searchProductId)->count() > 0)){
            return response()->json(['message' => 'ID de produto não encontrado'], 400);
        }
        $productMarker = ProductMarker::query()->where('product_id', $product)->get();
        return $productMarker;
    }
    public function destroy($id){
        //Deleta um marcador de um jogo
        try {
            $productMarker = ProductMarker::findOrFail($id);
            $productMarker->delete();
            return response()->json(['message' => 'Marcador deletado'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Marcador não encontrado'], 404);
        }catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
}