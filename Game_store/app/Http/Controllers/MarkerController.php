<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    public function index(){
        $marker = Marker::all();
        return $marker;
    }
    public function store(Request $request){
        try {
            if (count(array_diff(array_keys($request->all()), ['name', 'color'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'name'=>'required|unique:markers|string|max:150',
                'color'=>'string|max:100'
            ]);
            $data = $request->all();
            $marker = Marker::create($data);
            return $marker;
        } catch (Exception $exception) {
            return response()->json(['message'=> $exception->getMessage()],400);
        }
    }
    public function destroy($id){
        try {
            $marker = Marker::findOrFail($id);
            $marker->delete();
            return response()->json(['message' => 'Marcador deletado'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        } catch (Exception $exception){
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
}