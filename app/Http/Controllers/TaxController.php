<?php

namespace App\Http\Controllers;

use Facades\App\Services\TaxService;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function getTaxCost($toState, $toZip)
    {
        return json_encode(['success' => true, 'payload' => ['taxes' => (double)TaxService::getTaxCost($toState, $toZip)]], JSON_UNESCAPED_SLASHES);
    }
}
