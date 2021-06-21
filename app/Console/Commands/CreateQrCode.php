<?php

namespace App\Console\Commands;

use App\Models\ProductModel;
use Illuminate\Console\Command;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use function App\Helper\UploadCos;

class CreateQrCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:qr_code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成二维码';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        //1.获取未生成二维码的数据
        $data = ProductModel::where('status',0)->take(1)->get()->toArray();
        if (empty($data)) {
            return ;
        }
        foreach ($data as $v) {
            //2.生成二维码
            $bindUrl = env("QrCodeUrl") . $v['id'];
            $imgName = "test_".$v['id'].".png";
            QrCode::format('png')->generate($bindUrl,public_path('image/'.$imgName));
            $file = fopen(public_path('image/'.$imgName), "rb");
            //3.二维码上传到cos
            $key = "/qr_code/".$v['id'] .".png";//文件在桶中的位置
            $result = UploadCos($file,$key);
            $imgUrl = $result['Location'];
            //4.删除临时的文件
            unlink(public_path('image/'.$imgName));
            //5.更新数据库
            ProductModel::where('id',$v['id'])->update([
                'qr_code_url' => 'http://'.$imgUrl,
                'status' => 1
            ]);
            return ;
        }
    }
}
