<?php

namespace App\Services;

use App\Product;

class ProductService
{
    public function view($id)
    {
        if ($id == '') {
            $data = Product::all();
        } else {
            $data = Product::find($id);
        }
        $array = [
            'success' => true,
            'message' => $data,
        ];

        return $array;
    }

    public function search()
    {
        return $data = Product::all('name');
    }

    public function update($id, $name, $link)
    {
        $data = Product::where('id', $id)
            ->update(['name' => $name, 'link' => $link]);

        $array = [
            'success' => true,
            'message' => $data,
        ];

        return $array;
    }

    public function delete($id)
    {
        $data = Product::destroy($id);
        $array = [
            'success' => true,
            'message' => $data,
        ];

        return $array;
    }

    public function store($name, $link)
    {
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

        return $array;
    }
}
