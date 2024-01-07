<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AchievementController extends Controller
{
    public function store(Request $request)
    {
        try {
            if (count(array_diff(array_keys($request->all()), ['product_id', 'url', 'name', 'description'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'product_id' => 'integer|required',
                'url' => 'string|required|max:255',
                'name' => 'string|required|unique:achievements|max:150',
                'description' => 'required|max:500'
            ]);
            $searchProductId = $request->input('product_id');
            if (!(DB::table('products')->where('id', $searchProductId)->count() > 0)) {
                return response()->json(['message' => 'ID de produto não encontrado'], 400);
            }
            $data = $request->all();
            $achievement =  Achievement::create($data);
            return $achievement;
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
    public function index(Request $request)
    {
        $product = $request->query('product_id');
        $searchProductId = $request->input('product_id');
        if (!(DB::table('products')->where('id', $searchProductId)->count() > 0)) {
            return response()->json(['message' => 'ID de produto não encontrado'], 400);
        }
        $achievement = Achievement::query()->where('product_id', $product)->get();
        return $achievement;
    }
    public function update($id, Request $request)
    {
        try {
            if (count(array_diff(array_keys($request->all()), ['url', 'name', 'description'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'url' => 'string|required|max:255',
                'name' => 'string|required|unique:achievements|max:150',
                'description' => 'required|max:500'
            ]);
            $achievement = Achievement::findOrFail($id);
            $achievement->update($request->all());
            return response()->json(['message' => 'Conquista atualizada'], 200);;
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Conquista não encontrada'], 404);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
    public function destroy($id)
    {
        try {
            $achievement = Achievement::findOrFail($id);
            $achievement->delete();
            return response()->json(['message' => 'Conquista deletada'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Conquista não encontrada'], 404);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
}
