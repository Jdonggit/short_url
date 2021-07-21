<?php

namespace Tests\Unit;

use App\Models\ShortUrl;
// use PHPUnit\Framework\TestCase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{

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
            'short_url' => $random,
        ])->create();
        
        $short_url = url($random);
    
        $response = $this->get('/'.$random);
        
        $response->assertRedirect($original_address);
    }

    
}
