<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

/**
 * Description of RegionsController
 *
 * @author ahza0
 */
class RegionsController extends Controller {
    public function __construct() {
        parent::__construct('region', 'RegionID'); 
    }
}
