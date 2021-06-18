<?php
namespace App\Helper;
use Qcloud\Cos\Client;

//获取数据偏移
function getOffset($page, $limit)
{
    $offset = ceil(($page-1)*$limit);
    $offset = $offset < 0 ? 0 : $offset;
    return $offset;
}

function UploadCos($file,$name) {
    $secretId = env('COSV5_SECRET_ID'); //"云 API 密钥 SecretId";
    $secretKey = env('COSV5_SECRET_KEY'); //"云 API 密钥 SecretKey";
    $region = env('COSV5_REGION'); //设置一个默认的存储桶地域
    $cosClient = new Client(
        array(
            'region' => $region,
            'schema' => 'https', //协议头部，默认为http
            'credentials'=> array(
                'secretId'  => $secretId ,
                'secretKey' => $secretKey)
        )
    );
    $bucket = env('COSV5_BUCKET'); //存储桶名称
    return $cosClient->putObject(array(
            'Bucket' => $bucket,
            'Key' => $name,
            'Body' => $file
        )
    );
}
