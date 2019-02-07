<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends MainController
{
    protected $order_service ;
    public function __construct(OrderService $OrderService)
    {
        $this->order_service  =  $OrderService;
    }
    public function view(Request $request)
    {
        $id = '';
        if ($request->has('id')) {
            $id = $request->id;
        }
        return($this->order_service->view(parent::api($request), $id));
    }
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "duedate" => 'date|after:tomorrow|required',
            "reporter" => 'required',
            "concierge_fee" => "required|numeric|min:0",
            "product.*.name" => "required",
            "product.*.unit_of_measure" => "required",
            "product.*.qty" => "required|numeric|min:0",
            "product.*.price_per_1_qty" => "required|numeric|min:0",
        ]);
        if ($validator->fails()) {
            $array = array(
               'success' => false,
               'message' => $validator->errors()->first()
            );
            return $array;
        } else {
            return $this->order_service->store($request->product, $request->concierge_fee, parent::api($request), $request->duedate, $request->reporter);
        }
    }
    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "id" => "required",
            "duedate" => 'date|after:tomorrow|required',
            "reporter" => 'required',
            "concierge_fee" => "required|numeric|min:0",
            "product.*.name" => "required",
            "product.*.unit_of_measure" => "required",
            "product.*.qty" => "required|numeric|min:0",
            "product.*.price_per_1_qty" => "required|numeric|min:0",
        ]);
        if ($validator->fails()) {
            $array = array(
               'success' => false,
               'message' => $validator->errors()->first()
            );
            return $array;
        } else {
            return $this->order_service->update($request->product, $request->concierge_fee, $request->id, $request->duedate, $request->reporter);
        }
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
            return($this->order_service->delete($request->id));
        }
    }
    public function reporter(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "id" => "required",
            "flag" => "required"
        ]);
        if ($validator->fails()) {
            $array = array(
               'success' => false,
               'message' => $validator->errors()->first()
            );
            return $array;
        } else {
            return $this->order_service->reporter($request->id, $request->flag);
        }
    }
    public function update_finance(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "id" => "required",
            "concierge_fee" => "required|numeric|min:0",
            "payment" => "required",
            "product.*.name" => "required",
            "product.*.unit_of_measure" => "required",
            "product.*.actual_quantity" => "required|numeric|min:0",
            "product.*.actual_per_price" => "required|numeric|min:0",
        ]);
        if ($validator->fails()) {
            $array = array(
               'success' => false,
               'message' => $validator->errors()->first()
            );
            return $array;
        } else {
            return $this->order_service->update_finance($request->product, $request->concierge_fee, $request->id, $request->payment, parent::api($request));
        }
    }
}
