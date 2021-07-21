<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function getVisitorIp($url){
        return $this->where('ip', request()->ip())->where('short_url',$url)->first();
    }
}
