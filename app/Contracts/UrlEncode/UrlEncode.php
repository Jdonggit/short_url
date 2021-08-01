<?php
namespace App\Contracts\UrlEncode;
use Illuminate\Support\Str;

class UrlEncode
{
    public function encode()
    {
        if(env('SHORT_TYPE') == 'random'){
            $encode = Str::random(10);
        }elseif(env('SHORT_TYPE') == 'encrypt'){
            $encode = encrypt(Str::random(10));
        }
        
        return $encode;
    }
}