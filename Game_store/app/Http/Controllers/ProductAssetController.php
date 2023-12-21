<?php

namespace App\Http\Controllers;

use App\Models\ProductAsset;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductAssetController extends Controller
{
    public function store(Request $request){
        try {
            if (count(array_diff(array_keys($request->all()), ['product_id', 'name', 'url', 'types_games_assets'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'product_id'=>'integer|required',
                'name'=>'string|required|unique:products_assets|max:150',
                'url'=>'string|required|max:255',
                'types_games_assets'=>'required'
            ]);
            $searchProductId = $request->input('product_id');
            if (!(DB::table('products')->where('id', $searchProductId)->count() > 0)){
                return response()->json(['message' => 'ID de produto não encontrado'], 400);
            }
            $data = $request->all();
            $asset =  ProductAsset::create($data);
            return $asset;
        } catch (Exception $exception) {
            return response()->json(['message'=> $exception->getMessage()],400);
        }
    }
    public function index(Request $request){
        $product = $request->query('product_id');
        $searchProductId = $request->input('product_id');
        if (!(DB::table('products')->where('id', $searchProductId)->count() > 0)){
            return response()->json(['message' => 'ID de produto não encontrado'], 400);
        }
        $asset = ProductAsset::query()->where('product_id', $product)->get();
        return $asset;
    }
    public function update($id, Request $request){
        try {
            if (count(array_diff(array_keys($request->all()), ['name', 'url', 'types_games_assets'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'name'=>'string|required|unique:products_assets|max:150',
                'url'=>'string|required|max:255',
                'types_games_assets'=>'required'
            ]);
            $asset = ProductAsset::findOrFail($id);
            $asset->update($request->all());
            return response()->json(['message' => 'Asset atualizado'], 200);;
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Asset não encontrado'], 404);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
    public function destroy($id){
        try {
            $asset = ProductAsset::findOrFail($id);
            $asset->delete();
            return response()->json(['message' => 'Asset deletado'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Asset não encontrada'], 404);
        }catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
}