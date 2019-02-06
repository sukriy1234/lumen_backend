<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $product_service;
    public function __construct(ProductService $ProductService)
    {
        $this->product_service =  $ProductService;
    }
    public function view(Request $request)
    {
        $id = '';
        if ($request->has('id')) {
            $id = $request->id;
        }
        return($this->product_service->view($id));
    }
    public function search()
    {
        return($this->product_service->search());
    }
    public function detail(Request $request)
    {
        return($this->product_service->view($request->id));
    }
    public function delete(Request $request)
    {
        $validator = \Validator::make($request->all(), [
         "id"   => "required"
       ]);
        if ($validator->fails()) {
            $array = array(
               'success' => false,
               'message' => $validator->errors()->first()
            );
            return $array;
        } else {
            return($this->product_service->delete($request->id));
        }
    }
    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
         "name"   => "required"
       ]);
        if ($validator->fails()) {
            $array = array(
               'success' => false,
               'message' => $validator->errors()->first()
            );
            return $array;
        } else {
            return($this->product_service->update($request->id, $request->name, $request->link));
        }
    }
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
         "name"   => "required"
       ]);
        if ($validator->fails()) {
            $array = array(
               'success' => false,
               'message' => $validator->errors()->first()
            );
            return $array;
        } else {
            return($this->product_service->store($request->name, $request->link));
        }
    }
}
