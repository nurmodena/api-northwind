<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
/**
 * Description of Orders
 *
 * @author ahza0
 */
class OrdersController extends Controller {
    public function __construct() {
        parent::__construct('orders', 'OrderID', ["OrderID","CustomerID","ShipName","ShipAddress","ShipCity","ShipRegion", "ShipPostalCode", "ShipCountry"]); 
    }
    
    public function getByOrderDate(Request $req, $startdate, $enddate) { 
        $builder = $this->builder;
        $builder->whereBetween('OrderDate', [$startdate, $enddate]); 
        return response()->json($builder->get());
    }
    
    public function getByCustomers(Request $req, $customer) { 
        $builder = $this->builder;
        $builder->where("CustomerID", $customer); 
        return response()->json($builder->get());
    }
    
    public function getByShipCountry(Request $req, $shipCountry) { 
        $builder = $this->builder;
        $builder->where("ShipCountry", $shipCountry); 
        return response()->json($builder->get());
    }
    
    public function getByShipper(Request $req, $shipper) { 
        DB::enableQueryLog();
        $builder = DB::table("orders");
        $builder->join("shippers", "orders.ShipVia", "shippers.ShipperID")
                ->select("orders.*")
                ->where("shippers.CompanyName", urldecode($shipper));
        
         
        return response()->json($builder->get());
    }
    
    public function create(Request $req) {
        $data = $req->all();
        $order = $data['order'];
        $detail = $data['detail'];
        $id = $this->builder->insertGetId($order);
        $order[$this->key] = $id; 
        foreach($detail as &$item) {
            $item["OrderID"] = $id; 
        }
        DB::table("order details")->insert($detail);
        return response()->json(['success'=>true, 'data'=>['order'=>$order, 'detail'=>$detail]]);
    }
    
    public function update(Request $req, $id) {
        $data = $req->all();
        $item = $this->builder->where($this->key, $id);
        if ($item->count() == 0)
            return response()->json([
                        'success' => false,
                        'status' => 404,
                        'message' => 'Data not found'
                            ], 404);
        $order = $data['order'];
        $success = $item->update($order);
        if ($success) {
            DB::table("order details")->where('OrderID', $id)->delete();
            $detail = $data['detail'];
            foreach($detail as &$item) {
                $item["OrderID"] = $id; 
            }
            DB::table("order details")->insert($detail);
        }
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function delete(Request $req, $id) {
        $item = $this->builder->where($this->key, $id);
        if ($item->count() == 0)
            return response()->json([
                        'success' => false,
                        'status' => 404,
                        'message' => 'Data not found'
                            ], 404);
        DB::table("order details")->where('OrderID', $id)->delete();
        $success = $item->delete();
        return response()->json(['success' => true, 'message' => 'Delete data success']);
    }
}
