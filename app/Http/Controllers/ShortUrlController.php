<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Contracts\UrlEncode\UrlEncode;
use App\Repositories\ShortUrlRepository;
use App\Services\ShortUrlService;

class ShortUrlController extends Controller
{
    protected $repo, $visitor;

    public function __construct(ShortUrlRepository $repo, Visitor $visitor, ShortUrlService $shortUrlService)
    {
        $this->repo = $repo;
        $this->visitor = $visitor;
        $this->shortUrlService = $shortUrlService;
    }

    public function redirect()
    {
        try {
            $url = (trim(request()->getPathInfo(),'/'));
            // 查找短網址符合條件的資料 ＆ 檢查是否超過時限 
            $address = $this->shortUrlService->getShortUrl($url);
            
            return redirect()->to($address);
        } catch (\Throwable $th) {
            return response()->view('errors.404', [], 404);
        }
        
    }

    public function store(Request $request)
    {
        // 驗證輸入的是否是網址
        $request->validate([
            'original_address.*' => ['url'],
        ]);
        
        // 取得方才所新增的短網址, 顯示在首頁
        $links = $this->shortUrlService->insertShortUrlAndGetUrls($request);
        
        return view('home',[
            'links'=>$links
        ]);
    }
    
}
