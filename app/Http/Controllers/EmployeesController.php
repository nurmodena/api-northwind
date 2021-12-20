<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * Description of EmployeesController
 *
 * @author ahza0
 */
class EmployeesController extends Controller{
    public function __construct() {
        parent::__construct('employees', 'EmployeeID', ["EmployeeID","LastName","FirstName","Title","TitleOfCourtesy","Address","City","Region","PostalCode","Country","HomePhone","Extension"]); 
    } 
    
    public function get(Request $req) { 
        $pageSize = $req->get('pageSize', 10); 
        $sort = $req->get("sort", $this->key);
        $asc = $req->get("asc", "true");
        $builder = $this->builder; 
        $count = $builder->count(); 
        $builder->orderBy($sort, $asc == 'true' ? 'asc' : 'desc');
        $builder->paginate($pageSize);
        $data = $builder->get();
        foreach ($data as $value) {
            $value->Photo = base64_encode($value->Photo);
        }
        return response()->json([
                    'data' => $data,
                    'totalRow' => $count,
                    'totalPage' => ceil($count / $pageSize),
                    'sort' => $sort,
                    'directon' => $asc == 'asc' ? 'ASC' : 'DESC']);
    }
    
    public function getById(Request $req, $id) {
        $data = $this->builder->where($this->key, $id)->first();
        if (!isset($data)) 
            return response()->json([
                        'success' => false,
                        'status' => 404,
                        'type' => 'Not found',
                        'message' => 'Data not found',
                        'detail' => 'No row(s) found',
                        'timestamp' => time()], 404);
        $data->Photo = base64_encode($data->Photo);
        return response()->json($data);
    }
}
