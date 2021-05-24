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
        return $this->productModel->where('id',$params[id])->update(['is_deleted',Constants::IsDeletedYes]);
    }
}
