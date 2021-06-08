<?php

namespace App\Http\Controllers;

use App\Util\Constants;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function success ($data = []) {
        return response()->json([
            'status' => Constants::StatusCodeOk,
            'msg' => "ok",
            'data' => $data
        ]);
    }
    public function fail ($data = []) {
        return response()->json([
            'status' => Constants::StatusCodeFailure,
            'msg' => "fail",
            'data' => $data
        ]);
    }
}
