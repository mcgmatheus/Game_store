<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        return $products;
    }
    public function store(Request $request){
        try {
            if (count(array_diff(array_keys($request->all()), ['name', 'price', 'description', 'cover'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'name'=>'required|unique:products|string|max:150',
                'price'=>'numeric',
                'description'=>'string|max:5000',
                'cover'=>'string|max:255'],
            );
            $data = $request->all();
            $product =  Product::create($data);
            return $product;
        } catch (Exception $exception) {
            return response()->json(['message'=> $exception->getMessage()],400);
        }
    }
    public function show($id){
        try {
            $product = Product::findOrFail($id);
            return $product;
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
    public function update($id, Request $request){
        try {
            if (count(array_diff(array_keys($request->all()), ['name', 'price', 'description', 'cover'])) > 0) {
                return response()->json(['message' => 'Informações adicionais não permitidas'], 400);
            }
            $request->validate([
                'name'=>'unique:products|string|max:150',
                'price'=>'numeric',
                'description'=>'string|max:5000',
                'cover'=>'string|max:255'],

            );
            $product = Product::findOrFail($id);
            $product->update($request->all());
            return response()->json(['message' => 'Produto atualizado'], 200);;
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
    public function destroy($id){
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['message' => 'Produto deletado'], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
    }
}
