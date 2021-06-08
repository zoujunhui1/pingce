<?php

namespace App\Services;

use App\Models\AccountModel;
use App\Models\ProductModel;
use App\Util\Constants;

class ProductService
{
    protected $productModel;
    protected $accountModel;
    public function __construct(ProductModel $productModel,AccountModel $accountModel) {
        $this->productModel = $productModel;
        $this->accountModel = $accountModel;
    }

    public function AddProductSrv($params) {
        return $this->productModel->insert($params);
    }

    public function DelProductSrv ($params) {
        return $this->productModel->where('id',$params['id'])->update(['is_deleted'=>Constants::IsDeletedYes]);
    }

    public function EditProductSrv ($params) {
        return $this->productModel->where('id',$params['id'])->update($params);
    }

    public function GetProductListSrv ($search) {
        $list = $this->productModel->select()->where('is_deleted',Constants::IsDeletedNo);
        if (!empty($search['id'])) {
            $list = $list->where('id',$search['id']);
        }
        return $list->take($search['count'])->skip($search['offset'])->get()->toArray();
    }

    public function LoginSrv($params) {
        $data = [];
        $pwd =  md5($params['password'].Constants::Salt);
        $user = $this->accountModel->select()->where('name',$params['username'])
            ->where('password',$pwd)->get()->first();
        if (empty($user)){
            return $data;
        }
        //生成token
        $token =md5(Constants::Salt.time());
        $data =[
            'token' => $token,
            'username'=>$user->name,
        ];
        //更新token
        $this->accountModel->where('id',$user->id)->update(['token'=>$token]);
        return $data;
    }

}
