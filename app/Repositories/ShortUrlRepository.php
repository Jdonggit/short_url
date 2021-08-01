<?php

namespace App\Repositories;

use App\Models\ShortUrl;
use Carbon\Carbon;

class ShortUrlRepository extends BaseRepository
{
    protected function model() {
        return ShortUrl::class;
    }

    public function getShortUrl($url)
    {
        return $this->model()::where('short_url',$url)
                ->where('expired_time', '>',  Carbon::now())
                ->firstOrFail();
    }
    

    public function whereInGet($col, $val)
    {
        return  $this->model()::whereIn($col, $val)->get();
    }
}