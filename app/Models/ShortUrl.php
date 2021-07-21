<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ShortUrl extends Model
{
    use HasFactory;
    
    protected $guarded=[];


    public function getShortUrl($url)
    {
        return $this->where('short_url',$url)
                ->where('expired_time', '>=',  Carbon::now())
                ->firstOrFail();
    }

}
