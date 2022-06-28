## 予約システム (with Laravel)

##　ダウンロード方法

git clone

git clone https://github.com/TaiseiUgawa/laravel_reserveApp.git

ブランチを指定してダウンロードする場合

git clone -b ブランチ名　https://github.com/TaiseiUgawa/laravel_reserveApp.git

もしくはzipファイルの方をご活用ください

## インストール方法

- cd ReserveApp
- composer install
- npm install
- npm run dev

.env.example をコピーして　.envファイルの作成を行なってください。

.envファイルの中の下記をご利用の開発環境に合わせて変更してください。

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_reserve
DB_USERNAME=ureserve
DB_PASSWORD=password123

XAMPP/MAMPまたは他の開発環境でDB起動後

php artisan migrate:fresh --seed

を実行し、データベーステーブルとダミーデータが追加されて入ればOKです。

最後に
php artisan key:generate
でキーを生成後

php artisan serve
で簡易サーバーを立ち上げ、表示確認をしてください。

## インストール後の実施事項

画像のリンク
php artisan storage:link

プロフィールページで画像アップロード機能を使用する場合は、
.envファイルのAPP_URLを下記に変更してください。

APP_URL=http://127.0.0.1:8000

## その他　(フロントエンド補足)

Tailwind 3.x から　JustInTime機能により、
使ったHTML内のクラスのみ反映されるようになってますので
HTMLを編集する際は

npm run watch 
を実行しながら編集することを推奨します。

使用しない場合はHTMLを編集後

npm run dev 
でcss,jsファイルがコンパイルされ反映されますのでご使用ください。
