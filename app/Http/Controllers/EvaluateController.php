<?php

namespace App\Http\Controllers;

use App\Util\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use function App\Helper\getOffset;

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

    public function editProduct(Request $request) {
        $rules = [
            'id' => 'required',
        ];
        $validate = Validator::make($request->all(),$rules);
        if ($validate->fails()) {
            return response()->json([
                'status' => Constants::StatusCodeFailure,
                'msg' => "参数错误",
                'data' => []
            ]);
        }
        $this->productSrv->EditProductSrv($request->all());
        return $this->success([]);
    }

    public function getProductList(Request $request){
        $search = [];
        $params = $request->all();
        empty($params['page']) ? $search['page'] = 1 : $search['page'] = $params['page'];
        empty($params['count']) ? $search['count'] = 10 : $search['count'] = $params['count'];
        !empty($params['id']) && $search['id'] = $params['id'];
        $search['offset'] = getOffset($search['page'],$search['count']);
        $data = $this->productSrv->GetProductListSrv($search);
        QrCode::format('png')->generate('http://150.109.150.224/evaluate/list?id=18',public_path('image/test.png'));
        dd(1);
        return $this->success($data);
    }
}
