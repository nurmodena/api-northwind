<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller {
    public function __construct() {
        parent::__construct('customers', 'CustomerID', ["CustomerID", "CompanyName", "ContactName", "ContactTitle", "Address", "City", "Country"]); 
    } 
}
