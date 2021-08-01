<?php

namespace App\Services;

use App\Repositories\ShortUrlRepository;
use App\Repositories\VisitorRepository;
use App\Contracts\UrlEncode\UrlEncode;
use Carbon\Carbon;

class ShortUrlService extends BaseService
{
    private $emailMessenger;
    private $visitorRepo;

    // DI 注入各種會用到的 repositories 和其他類別實例
    public function __construct(ShortUrlRepository $shortUrlRepo, VisitorRepository $visitorRepo, UrlEncode $urlEncode)
    {
        // 注入 BaseService 初始主要的 repository 和 resource
        parent::__construct($shortUrlRepo);
        $this->visitorRepo = $visitorRepo;
        $this->urlEncode = $urlEncode;
    }

    public function insertShortUrlAndGetUrls($input)
    {
        // 批次新增陣列
        $insert = [];
        // 暫存encode 陣列
        $encode_ary = [];
        // 取得現在時間
        $now = Carbon::now();

        foreach ($input->original_address as $key => $value) {
            $encode = $this->urlEncode->encode();
            
            // 把編碼網址放到暫存陣列中
            $encode_ary[] = $encode;
            $insert[] = [
                'original_address'=> $value,
                'short_url' => $encode,
                'created_at' => date("Y-m-d H:i:s"),
                'expired_time' => $now->addHours(1), // 現在時間加上一小時 ＝ 短網址時限
            ];
        }
        
        $this->repository->insert($insert);

        // 取得方才所新增的短網址, 顯示在首頁

        $links = $this->repository->whereInGet('short_url', $encode_ary);

        return $links;

    }
}
