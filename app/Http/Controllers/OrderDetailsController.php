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
 * Description of OrderDetailsController
 *
 * @author ahza0
 */
class OrderDetailsController extends Controller{
    public function __construct() {
        parent::__construct('order details', 'OrderID'); 
    }
    
    public function get(Request $req) {
        $pageSize = $req->get('pageSize', 10);
        $sort = $req->get("sort", $this->key);
        $asc = $req->get("asc", "true");
        $builder = DB::table("order details", "od");
        $builder->selectRaw("OrderID, od.ProductID, ProductName, CategoryName, CompanyName as SupplierName, od.UnitPrice, Quantity, Discount")
                ->join("products", "products.ProductID", "od.ProductID")
                ->join("categories", "categories.CategoryID", "products.CategoryID")
                ->join("suppliers", "suppliers.SupplierID", "products.SupplierID");
        $productId = $req->get('productId');
        if (isset($productId)) {
            $builder->where("od.ProductID", $productId);
        }
        $count = $builder->count();
        $builder->orderBy($sort, $asc == 'true' ? 'asc' : 'desc');
        $builder->paginate($pageSize);
        return response()->json([
                    'data' => $builder->get(),
                    'totalRow' => $count,
                    'totalPage' => ceil($count / $pageSize),
                    'sort' => $sort,
                    'directon' => $asc == 'asc' ? 'ASC' : 'DESC']);
    }
    public function getById(Request $req, $id) {
        $data = $this->builder->where($this->key, $id)->get(); 
        if (!isset($data)) return response()->json (['success'=>false, 'status'=>404, 'message'=>'Data not found'], 404);
        return response()->json($data);
    }
    
    public function getByProductId(Request $req, $id1, $id2) {
        $data = $this->builder->where($this->key, $id1)->where('ProductID', $id2)->first(); 
        if (!isset($data)) return response()->json (['success'=>false, 'status'=>404, 'message'=>'Data not found'], 404);
        return response()->json($data);
    }
}
