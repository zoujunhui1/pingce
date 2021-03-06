<?php

namespace App\Http\Controllers;

use App\Util\Constants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use function App\Helper\getOffset;
use Illuminate\Support\Facades\Storage;

class EvaluateController extends Controller
{
    public $productSrv;
    public function __construct(Services\ProductService $productService){
        $this->productSrv = $productService;
    }

    public function addProduct(Request $request) {
        $rules = [
            'name'            => 'required',
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
        $res = $this->productSrv->GetProductListSrv($search);
        $res['page'] = $search['page'];
        $res['count'] = $search['count'];
        return $this->success($res);
    }

    public function getProductById(Request $request){
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
        $params = $request->all();
        $res = $this->productSrv->GetProductByIdSrv($params);
        return $this->success($res);
    }

    public function uploadFile(Request $request) {
        //对文件进行判断
        $file = $request->file('file');
        if(empty($file)) {
            return response()->json([
                'status' => Constants::StatusCodeFailure,
                'msg' => "参数错误",
                'data' => []
            ]);
        }
        //上传文件
        $disk = Storage::disk('cosv5');
        $file_content = $disk->put('/pingce/product',$file);//第一个参数是你储存桶里想要放置文件的路径，第二个参数是文件对象
        $file_url = $disk->url($file_content);//获取到文件的线上地址
        if (empty($file_url)){
            return $this->fail([]);
        }
        $tmp = parse_url($file_url);
        $url = $tmp['scheme']."://" . $tmp['host'] . $tmp['path'];
        return $this->success(['file_url' => $url]);
    }

    public function login (Request $request) {
        $rules = [
            'username' => 'required|filled|string|between:6,10',
            'password' => 'required|filled|string|between:8,15',
        ];
        $validate = Validator::make($request->all(),$rules);
        if ($validate->fails()) {
            return response()->json([
                'status' => Constants::StatusCodeFailure,
                'msg' => "参数错误",
                'data' => []
            ]);
        }
        $params = $request->all();
        $data = $this->productSrv->LoginSrv($params);
        if (empty($data)) {
            return $this->fail([]);
        }
        return $this->success($data);
    }
}
