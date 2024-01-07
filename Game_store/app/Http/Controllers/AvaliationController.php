<?php

namespace App\Http\Controllers;

use App\Models\Avaliation;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AvaliationController extends Controller
{
    public function store(Request $request){
        try {
            if (count(array_diff(array_keys($request->all()), ['product_id', 'avaliation', 'recommended'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'product_id'=>'integer|required',
                'avaliation'=>'string|required|max:1000',
                'recommended'=>'boolean|required'
            ]);
            $searchProductId = $request->input('product_id');
            if (!(DB::table('products')->where('id', $searchProductId)->count() > 0)){
                return response()->json(['message' => 'ID de produto não encontrado'], 400);
            }
            $data = $request->all();
            $avaliation =  Avaliation::create($data);
            return $avaliation;
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
        $avaliation = Avaliation::query()->where('product_id', $product)->get();
        return $avaliation;
    }
    public function update($id, Request $request){
        try {
            if (count(array_diff(array_keys($request->all()), ['avaliation', 'recommended'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'avaliation'=>'string|required|max:1000',
                'recommended'=>'boolean|required'
            ]);
            $avaliation = Avaliation::findOrFail($id);
            $avaliation->update($request->all());
            return response()->json(['message' => 'Avaliação atualizada'], 200);;
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Avaliação não encontrada'], 404);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
    public function destroy($id){
        try {
            $avaliation = Avaliation::findOrFail($id);
            $avaliation->delete();
            return response()->json(['message' => 'Avaliação deletada'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Avaliação não encontrada'], 404);
        }catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
}
