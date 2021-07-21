簡易短網址

首頁
![image](https://github.com/Jdonggit/short_url/blob/master/url.jpg)

新增短網址
![image](https://github.com/Jdonggit/short_url/blob/master/url_to.jpg)

測試結果
![image](https://github.com/Jdonggit/short_url/blob/master/testing.jpg)

### (1) 建立縮網址功能

- 檢查 URL 是否符合標準
- 同一個網址，每次產生結果不一樣
- 會有 兩種 Encode 演算法，可透過環境設定切換
-  .env檔案 
- - - SHORT_TYPE = `random` 以`Str::random` 
- - - SHORT_TYPE = `encrypt` 以`encrypt`

### (2) 批次建立縮網址功能

- 可同時建立多筆縮網址

### (3) 縮網址導回原始網址
以下項目有寫測試

- 有效時間 1小時 （expired_time 創建時間 加一小時為有效時間）
- 超過有效期間，回應 404
- 紀錄點擊次數，同一個 IP 只算一次 （visitors 資料表 一個ＩＰ 點擊一個短網址算一次點擊次數）
- 成功導回正確網址 
