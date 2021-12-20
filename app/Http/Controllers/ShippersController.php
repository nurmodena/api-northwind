<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

/**
 * Description of ShippersController
 *
 * @author ahza0
 */
class ShippersController extends Controller {
    public function __construct() {
        parent::__construct('shippers', 'ShipperID', ["CompanyName", "Phone"]); 
    }
}
