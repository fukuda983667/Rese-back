<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Region;

class RegionController extends Controller
{
    // 全地域のリストを取得
    public function getRegions()
    {
        $regions = Region::all();

        return response()->json(compact('regions'),200);
    }
}
