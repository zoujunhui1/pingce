<?php

namespace App\Services;

use App\Models\ProductModel;
use App\Util\Constants;

class ProductService
{
    protected $productModel;
    public function __construct(ProductModel $productModel)
    {
        $this->productModel = $productModel;
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

}
