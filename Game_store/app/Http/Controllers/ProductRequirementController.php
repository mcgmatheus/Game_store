<?php

namespace App\Http\Controllers;

use App\Models\ProductRequirement;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ProductRequirementController extends Controller
{
    public function store(Request $request)
    {
        try {
            if (count(array_diff(array_keys($request->all()), ['product_id', 'operational_system', 'memory', 'storage', 'observations', 'type'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'product_id' => 'integer|required',
                'operational_system' => 'string|required|max:150',
                'memory' => 'string|required|max:150',
                'storage' => 'string|required|max:150',
                'observations' => 'string|required|max:500',
                'type' => 'string|required'
            ]);
            $searchProductId = $request->input('product_id');
            if (!(DB::table('products')->where('id', $searchProductId)->count() > 0)) {
                return response()->json(['message' => 'ID de produto não encontrado'], 400);
            }
            $data = $request->all();
            $productRequirementTypeExists = ProductRequirement::query()
                ->where('product_id', $data['product_id'])
                ->where('type', $data['type'])
                ->first();

            if ($productRequirementTypeExists) {
                return response()->json(['message' => 'O requisito já foi cadastrado'], 409);
            }
            $requirement =  ProductRequirement::create($data);
            return $requirement;
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
        $requirement = ProductRequirement::query()->where('product_id', $product)->get();
        return $requirement;
    }
    public function update($id, Request $request)
    {
        try {
            if (count(array_diff(array_keys($request->all()), ['operational_system', 'memory', 'storage', 'observations', 'type'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'operational_system' => 'string|required|max:150',
                'memory' => 'string|required|max:150',
                'storage' => 'string|required|max:150',
                'observations' => 'string|required|max:500',
                'type' => 'string|required'
            ]);
            $requirement = ProductRequirement::findOrFail($id);
            $requirement->update($request->all());
            return response()->json(['message' => 'Requisitos atualizados'], 200);;
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Requisito não encontrado'], 404);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
    public function destroy($id)
    {
        try {
            $requirement = ProductRequirement::findOrFail($id);
            $requirement->delete();
            return response()->json(['message' => 'Requisitos deletados'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Requisito não encontrado'], 404);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
}
