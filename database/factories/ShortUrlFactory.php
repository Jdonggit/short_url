<?php

namespace Database\Factories;

use App\Models\ShortUrl;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShortUrlFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShortUrl::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        if(env('SHORT_TYPE') == 'random'){
            $encode = Str::random(10);
        }elseif(env('SHORT_TYPE') == 'encrypt'){
            $encode = encrypt(Str::random(10));
        }


        return [
            //
            'original_address' => 'http://'.Str::random(10).'.com',
            'short_url' => $encode,
            'expired_time' => Carbon::now()->addHours(1),
            'click_count' => 0
        ];
    }
}
