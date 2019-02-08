<?php

namespace App\Services;

use App\Product;

class ProductService
{
    public function view($id)
    {
        $array = [];

        try {
            $data = null;
            if ($id == '') {
                $data = Product::all();
            } else {
                $data = Product::find($id);
            }
            $array = [
                'success' => true,
                'message' => $data,
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            $array = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        return $array;
    }

    public function search()
    {
        return Product::all('name');
    }

    public function update($id, $name, $link)
    {
        $array = [];

        try {
            $data = Product::where('name', $name)->where('id', '<>', $id)->count();
            if ($data == 0) {
                $data = Product::where('id', $id)
                    ->update(['name' => $name, 'link' => $link]);

                $array = [
                    'success' => true,
                    'message' => $data,
                ];
            } else {
                $array = [
                    'success' => false,
                    'message' => 'Already have item with that name',
                ];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $array = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        return $array;
    }

    public function delete($id)
    {
        $array = [];

        try {
            $data = Product::where('id', $id)->first();
            if ($data) {
                $data = Product::destroy($id);
                $array = [
                    'success' => true,
                    'message' => $data,
                ];
            } else {
                $array = [
                    'success' => false,
                    'message' => 'NOT FOUND',
                ];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $array = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        return $array;
    }

    public function store($name, $link)
    {
        $array = [];

        try {
            $data = Product::where('name', $name)->count();
            if ($data == 0) {
                $product = new Product();
                $product->name = $name;
                $product->link = $link;
                $product->save();

                $array = [
                    'success' => true,
                    'message' => 'Success Input',
                ];
            } else {
                $array = [
                    'success' => false,
                    'message' => 'Already have item with that name',
                ];
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $array = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        return $array;
    }
}
