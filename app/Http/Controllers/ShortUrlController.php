<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShortUrlController extends Controller
{
    protected $shortUrl, $visitor;

    public function __construct(ShortUrl $shortUrl, Visitor $visitor)
    {
        $this->shortUrl = $shortUrl;
        $this->visitor = $visitor;
    }

    public function redirect()
    {
        $url = (trim(request()->getPathInfo(),'/'));
        
        try {

            // 取得現在時間
            $now = Carbon::now();
            
            // 查找短網址符合條件的資料 ＆ 檢查是否超過時限 
            $short_url = $this->shortUrl->getShortUrl($url);
            
            $address = $short_url->original_address;

            // 訪問者是否有點擊過短網址
            $visitor = $this->visitor->getVisitorIp($url);
            // 沒訪問過 新增訪問者ip 並 新增點擊率
            if(!isset($visitor)){
                $this->visitor->create([
                    'ip' => request()->ip(),
                    'short_url' => $url,
                ]);
                $short_url->click_count += 1;
                $short_url->save(); 
            }
            
            return redirect()->to($address);
        } catch (\Throwable $th) {
            abort(404);
        }
        
    }

    public function store(Request $request)
    {
        // 驗證輸入的是否是網址
        $request->validate([
            'original_address.*' => ['url'],
        ]);
        // 批次新增陣列
        $insert = [];
        // 暫存encode 陣列
        $encode_ary = [];
        // 取得現在時間
        $now = Carbon::now();
        foreach ($request->original_address as $key => $value) {
            $encode = $this->encode_url();
            
            // 把編碼網址放到暫存陣列中
            $encode_ary[] = $encode;
            $insert[] = [
                'original_address'=> $value,
                'short_url' => $encode,
                'created_at' => date("Y-m-d H:i:s"),
                'expired_time' => $now->addHours(1), // 現在時間加上一小時 ＝ 短網址時限
            ];
        }
        
        $this->shortUrl->insert($insert);

        // 取得方才所新增的短網址, 顯示在首頁
        $links = $this->shortUrl->whereIn('short_url', $encode_ary)->get();
        
        return view('home',[
            'links'=>$links
        ]);
    }
    
    public function encode_url(){
        if(env('SHORT_TYPE') == 'random'){
            $encode = Str::random(10);
        }elseif(env('SHORT_TYPE') == 'encrypt'){
            $encode = encrypt(Str::random(10));
        }

        return $encode;
    }
}
