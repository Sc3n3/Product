<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @param CreateProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(CreateProductRequest $request)
    {
        try {
            $product = new Product();
            $product->user_id = $request->user()->getKey();
            $product->fill($request->toArray());
            $product->saveOrFail();

            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);

        }

    }
}
