<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        try {
            if (count(array_diff(array_keys($request->all()), ['name', 'description'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'name' => 'required|unique:categories|string|max:150',
                'description' => 'required|string|max:500'
            ]);
            $data = $request->all();
            $category = Category::create($data);
            return $category;
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
    public function index()
    {
        $categories = Category::all();
        return $categories;
    }
    public function update($id, Request $request)
    {
        try {
            if (count(array_diff(array_keys($request->all()), ['name', 'description'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'name' => 'unique:categories|string|max:150',
                'description' => 'string|max:500'
            ]);
            $category = Category::findOrFail($id);
            $category->update($request->all());
            return response()->json(['message' => 'Categoria atualizado'], 200);;
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(['message' => 'Categoria deletada'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Categoria não encontrada'], 404);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
}
