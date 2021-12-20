<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

/**
 * Description of ProductsController
 *
 * @author ahza0
 */
class ProductsController extends Controller{
    public function __construct() {
        parent::__construct('products', 'ProductID', ["ProductID","ProductName","QuantityPerUnit"]); 
    }
}
