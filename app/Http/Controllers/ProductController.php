<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = app('db')->select("SELECT * FROM products WHERE deleted_at IS NULL");

        if (request()->has('search')) {
            $products = app('db')->select("SELECT * FROM products WHERE name LIKE '%" . request()->search . "%' AND deleted_at IS NULL");
        }

        $data = [
            'products' => $products,
        ];

        $apiService = new ApiService();

        return $apiService->response($data, 'List All Products', 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validasi request
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);

        // ambil data request
        $name = $request->input('name');
        $price = $request->input('price');
        $description = $request->input('description');

        // simpan data ke database
        $query = app('db')->insert("INSERT INTO products (name, price, description) VALUES ('$name', '$price', '$description')");

        // cek apakah proses simpan berhasil
        if ($query) {
            $data = [
                'name' => $name,
                'price' => $price,
                'description' => $description,
            ];

            $apiService = new ApiService();

            return $apiService->response($data, 'Product Created', 201);
        } else {
            $data = [
                'message' => 'Create Product Failed',
            ];

            $apiService = new ApiService();

            return $apiService->response($data, 'Create Product Failed', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = app('db')->select("SELECT * FROM products WHERE id = '$id' AND deleted_at IS NULL");

        $apiService = new ApiService();

        if (!$products) {
            $data = [
                'message' => 'Product Not Found',
            ];

            return $apiService->response($data, 'Product Not Found', 404);
        }

        $data = [
            'products' => $products,
        ];

        return $apiService->response($data, 'Product Found', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validasi request
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
        ]);

        // ambil data request
        $name = $request->input('name');
        $price = $request->input('price');
        $description = $request->input('description');

        // simpan data ke database
        $query = app('db')->update("UPDATE products SET name = '$name', price = '$price', description = '$description' WHERE id = '$id'");

        // cek apakah proses simpan berhasil
        if ($query) {
            $data = [
                'name' => $name,
                'price' => $price,
                'description' => $description,
            ];

            $apiService = new ApiService();

            return $apiService->response($data, 'Product Updated', 201);
        } else {
            $data = [
                'message' => 'Update Product Failed',
            ];

            $apiService = new ApiService();

            return $apiService->response($data, 'Update Product Failed', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // cek apakah product ada di database
        $product = app('db')->select("SELECT * FROM products WHERE id = '$id' AND deleted_at IS NULL");

        if (!$product) {
            $data = [
                'message' => 'Product Not Found',
            ];

            $apiService = new ApiService();

            return $apiService->response($data, 'Product Not Found', 404);
        }

        // soft delete product
        $query = app('db')->update("UPDATE products SET deleted_at = NOW() WHERE id = '$id'");

        if ($query) {
            $data = [
                'message' => 'Product Deleted',
            ];

            $apiService = new ApiService();

            return $apiService->response($data, 'Product Deleted', 200);
        } else {
            $data = [
                'message' => 'Delete Product Failed',
            ];

            $apiService = new ApiService();

            return $apiService->response($data, 'Delete Product Failed', 400);
        }
    }
}
