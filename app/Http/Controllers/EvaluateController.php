<?php

namespace App\Http\Controllers;

use App\Util\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services;

class EvaluateController extends Controller
{
    public $productSrv;
    public function __construct(Services\ProductService $productService){
        $this->productSrv = $productService;
    }

    public function addProduct(Request $request) {
        $rules = [
            'name'            => 'required',
            'identify_result' => 'required',
        ];
        $validate = Validator::make($request->all(),$rules);
        if ($validate->fails()) {
            return response()->json([
                'status' => Constants::StatusCodeFailure,
                'msg' => "参数错误",
                'data' => []
            ]);
        }
        $this->productSrv->AddProductSrv($request->all());
        return $this->success([]);
    }
    public function delProduct(Request $request) {
        $rules = [
            'id'            => 'required',
        ];
        $validate = Validator::make($request->all(),$rules);
        if ($validate->fails()) {
            return response()->json([
                'status' => Constants::StatusCodeFailure,
                'msg' => "参数错误",
                'data' => []
            ]);
        }
        $this->productSrv->DelProductSrv($request->all());
        return $this->success([]);
    }

}
