<?php

namespace Tests\Unit;

use App\Models\ShortUrl;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }
    /**
     * 測試新增短網址
     *
     * @return void
     */
    public function testCreateShortUrl()
    {
        $url = 'https://'.Str::random(10).'.com';
        
        $response = $this->post('/', [
            'original_address' => [$url],
        ]);
        
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('short_urls', [
            'original_address' => $url,
        ]);
    }


    /**
     * 測試短網址導向原網址
     *
     * @return void
     */
    public function testUrlDedirect()
    {
        $original_address = 'https://google.com.tw';
        $random = Str::random(10);
        ShortUrl::factory([
            'original_address' => $original_address,
            'expired_time' => Carbon::now()->addHours(1),
            'short_url' => $random,
        ])->create();
        
        $this->assertDatabaseHas('short_urls', [
            'short_url' => $random,
            'expired_time' => Carbon::now()->addHours(1),
        ]);

        $short_url = url($random);
    
        $response = $this->get('/'.$random);
        
        $response->assertRedirect($original_address);
    }

    /**
     * 測試網址時效
     *
     * @return void
     */
    public function testUrlExpiredTime()
    {
        $original_address = 'https://google.com.tw';
        $random = Str::random(10);
        $expired_time = Carbon::now()->addHours(1);
        ShortUrl::factory([
            'original_address' => $original_address,
            'expired_time' => $expired_time,
            'short_url' => $random,
        ])->create();

        $short_url = url($random);
    
        // 調整未來一小時
        $this->travel(1)->hours();
        // 超過時效看到404
        $response = $this->get('/'.$random);

        $response->assertStatus(404);
    }
    

    /**
     * 測試點擊數
     *
     * @return void
     */
    public function testClickCount()
    {
        $original_address = 'https://google.com.tw';
        $random = Str::random(10);
        $expired_time = Carbon::now()->addHours(1);
        $short_data = ShortUrl::factory([
            'original_address' => $original_address,
            'expired_time' => $expired_time,
            'short_url' => $random,
        ])->create();
        $short_url = url($random);
    
        // 仿造ip
        $this->withServerVariables(['REMOTE_ADDR' => '10.1.0.1']);

        // 訪問兩次 點擊數要為1
        $response = $this->get('/'.$random);

        $this->assertDatabaseHas('short_urls', [
            'id' =>  $short_data->id,
            'click_count' => 1,
        ]);

        
        $response = $this->get('/'.$random);
        
        $this->assertDatabaseHas('short_urls', [
            'id' =>  $short_data->id,
            'click_count' => 1,
        ]);


        // 仿造另一個ip 點擊數為2
        $this->withServerVariables(['REMOTE_ADDR' => '10.2.2.2']);
        $response = $this->get('/'.$random);
        
        $this->assertDatabaseHas('short_urls', [
            'id' =>  $short_data->id,
            'click_count' => 2,
        ]);
    }
}
