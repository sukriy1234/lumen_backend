<?php

namespace App\Services;

use App\Order;
use App\OrderDetail;
use App\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function view($api_token, $id)
    {
        $array = [];

        try {
            $data = null;
            if ($id == '') {
                $user = User::where('api_token', $api_token)->select('id', 'flag')->first();

                $data = new Order();
                if ($user->flag == 2) {
                    $data = $data->orwhereNotNull('orders.respond_reporter');
                }
                $data = $data
                    ->orwhere('orders.input_user', $user->id)
                    ->orwhere('orders.reporter', $user->id)
                    ->leftJoin('users as input', 'input.id', '=', 'orders.input_user')
                    ->leftJoin('users as report', 'report.id', '=', 'orders.reporter')
                    ->leftJoin('users as finance', 'finance.id', '=', 'orders.finance')
                    ->leftJoin('flags', 'flags.id', '=', 'orders.flag')
                    ->select('orders.*', 'input.username as input_username', 'report.username as report_username', 'finance.username as finance_username', 'flags.status')
                    ->get();
            } else {
                $data = Order
                 ::where('orders.id', $id)
                     ->join('order_details', 'order_details.id', '=', 'orders.id')
                     ->leftJoin('users as input', 'input.id', '=', 'orders.input_user')
                     ->leftJoin('users as report', 'report.id', '=', 'orders.reporter')
                     ->leftJoin('users as finance', 'finance.id', '=', 'orders.finance')
                     ->leftJoin('flags', 'flags.id', '=', 'orders.flag')
                     ->select('orders.*', 'order_details.*', 'input.username as input_username', 'report.username as reporter_username', 'finance.username as finance_username', 'flags.status')
                     ->get();
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

    public function store($product, $concierge_fee, $api_token, $duedate, $reporter)
    {
        $array = [];

        try {
            DB::beginTransaction();

            $reporter = User::where('username', $reporter)->select('id')->first();
            if ($reporter == false) {
                $array = [
                    'success' => false,
                    'message' => 'reporter not found',
                ];

                return $array;
            }

            $data = User::where('api_token', $api_token)->first();
            $user_input = $data->id;

            $data = new Order();
            $data->input_user = $user_input;
            $data->total = 0;
            $data->duedate = $duedate;
            $data->reporter = $reporter->id;
            $data->save();
            $id_order = $data->id;

            $total = $concierge_fee;

            foreach ($product as $key => $value) {
                $data = new OrderDetail();
                $subtotal = str_replace(',', '', $value['qty']) * str_replace(',', '', $value['price_per_1_qty']);
                $total += $subtotal;
                $data->id = $id_order;
                $data->name = $value['name'];
                $data->unit_of_measure = $value['unit_of_measure'];
                if (isset($value['qty'])) {
                    $data->qty = $value['qty'];
                }
                if (isset($value['price_per_1_qty'])) {
                    $data->price_per_1_qty = $value['price_per_1_qty'];
                }
                if (isset($value['actual_quantity'])) {
                    $data->actual_quantity = $value['actual_quantity'];
                }
                if (isset($value['actual_per_price'])) {
                    $data->actual_per_price = $value['actual_per_price'];
                }
                if (isset($value['actual_price'])) {
                    $data->actual_price = $subtotal;
                }
                if (isset($value['note'])) {
                    $data->note = $value['note'];
                }
                $data->save();
            }
            $data = new OrderDetail();
            $data->id = $id_order;
            $data->name = 'concierge fee';
            $data->actual_price = $concierge_fee;
            $data->save();

            $order = Order::find($id_order);
            $order->total = $total;
            $order->save();

            $array = [
                'success' => true,
                'message' => 'success',
            ];
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            $array = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            DB::rollBack();
        }

        return $array;
    }

    public function update($product, $concierge_fee, $id_order, $duedate, $reporter)
    {
        $array = [];

        try {
            DB::beginTransaction();
            $data = OrderDetail::destroy($id_order);
            $reporter = User::where('username', $reporter)->select('id')->first();
            if ($reporter == false) {
                $array = [
                    'success' => false,
                    'message' => 'reporter not found',
                ];

                return $array;
            }

            $total = $concierge_fee;
            foreach ($product as $key => $value) {
                $data = new OrderDetail();
                $subtotal = str_replace(',', '', $value['qty']) * str_replace(',', '', $value['price_per_1_qty']);
                $total += $subtotal;
                $data->id = $id_order;
                $data->name = $value['name'];
                $data->unit_of_measure = $value['unit_of_measure'];
                if (isset($value['qty'])) {
                    $data->qty = $value['qty'];
                }
                if (isset($value['price_per_1_qty'])) {
                    $data->price_per_1_qty = $value['price_per_1_qty'];
                }
                if (isset($value['actual_quantity'])) {
                    $data->actual_quantity = $value['actual_quantity'];
                }
                if (isset($value['actual_per_price'])) {
                    $data->actual_per_price = $value['actual_per_price'];
                }
                if (isset($value['actual_price'])) {
                    $data->actual_price = $subtotal;
                }
                if (isset($value['note'])) {
                    $data->note = $value['note'];
                }
                $data->save();
            }
            $data = new OrderDetail();
            $data->id = $id_order;
            $data->name = 'concierge fee';
            $data->actual_price = $concierge_fee;
            $data->save();

            $order = Order::find($id_order);
            $order->total = $total;
            $order->duedate = $duedate;
            $order->reporter = $reporter->id;
            $order->save();

            $array = [
                'success' => true,
                'message' => 'success',
            ];
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            $array = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            DB::rollBack();
        }

        return $array;
    }

    public function delete($id)
    {
        $array = [];

        try {
            DB::beginTransaction();
            $data = Order::destroy($id);
            $data = OrderDetail::destroy($id);
            $array = [
                'success' => true,
                'message' => $data,
            ];
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            $array = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            DB::rollBack();
        }

        return $array;
    }

    public function reporter($id_order, $flag)
    {
        $array = [];

        try {
            date_default_timezone_set('Asia/Bangkok');

            $order = Order::find($id_order);
            $order->flag = $flag;
            $order->respond_reporter = date('Y-m-d H:i:s', time());
            $order->save();

            $array = [
                'success' => true,
                'message' => 'success',
            ];
        } catch (\Illuminate\Database\QueryException $e) {
            $array = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

        return $array;
    }

    public function update_finance($product, $concierge_fee, $id_order, $payment, $api_token)
    {
        $array = [];

        try {
            DB::beginTransaction();
            date_default_timezone_set('Asia/Bangkok');

            $data = OrderDetail::destroy($id_order);
            $data = User::where('api_token', $api_token)->first();
            $finance = $data->id;

            $total = $concierge_fee;
            foreach ($product as $key => $value) {
                $data = new OrderDetail();
                $subtotal = str_replace(',', '', $value['actual_quantity']) * str_replace(',', '', $value['actual_per_price']);
                $total += $subtotal;
                $data->id = $id_order;
                $data->name = $value['name'];
                $data->unit_of_measure = $value['unit_of_measure'];
                if (isset($value['qty'])) {
                    $data->qty = $value['qty'];
                }
                if (isset($value['price_per_1_qty'])) {
                    $data->price_per_1_qty = $value['price_per_1_qty'];
                }
                if (isset($value['actual_quantity'])) {
                    $data->actual_quantity = $value['actual_quantity'];
                }
                if (isset($value['actual_per_price'])) {
                    $data->actual_per_price = $value['actual_per_price'];
                }
                if (isset($value['actual_price'])) {
                    $data->actual_price = $subtotal;
                }
                if (isset($value['note'])) {
                    $data->note = $value['note'];
                }
                $data->save();
            }
            $data = new OrderDetail();
            $data->id = $id_order;
            $data->name = 'concierge fee';
            $data->actual_price = $concierge_fee;
            $data->save();

            $order = Order::find($id_order);
            $order->total = $total;
            $order->payment = $payment;
            $order->finance = $finance;
            $order->respond_finance = date('Y-m-d H:i:s', time());
            $order->flag = 3;
            $order->save();

            $array = [
                'success' => true,
                'message' => 'success',
            ];
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            $array = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            DB::rollBack();
        }

        return $array;
    }
}
