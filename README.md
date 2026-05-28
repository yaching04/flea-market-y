
# coachtech フリマアプリ


# 1.環境構築

cd coachtech/laravel

git clone git@github.com:Estra-Coachtech/laravel-docker-template.git

mv laravel-docker-template flea-market


**GutHubで新しいリモートリポジトリ作成「flea-market」**

cd flea-market

git remote set-url origin git@github.com:yaching04/flea-market.git

git remote -v

git add .

git commit -m "リモートリポジトリの変更"

git push origin main


**Dockerの設定**

docker-compose up -d --build

**Laravel のパッケージのインストール**

docker-compose exec php bash

composer install

**.envファイルの作成・修正**

cp .env.example .env

---

# 2.マイグレーション作成

php artisan make:model Condition -m

php artisan make:model Category -m

php artisan make:model Item -mcr

php artisan make:model Like -m

php artisan make:model Comment -m

php artisan make:model Purchase -mcr

php artisan make:model ItemCategory -m

php artisan make:migration add_custom_columns_to_users_table --table=users

---

# 開発環境

- PHP 8.1

- Laravel 10.x

- MySQL 8.0

- Docker + Docker Compose

- Stripe（決済テスト）

---

# 各画面のurl

ログイン画面
http://localhost/login

会員登録画面
http://localhost/register

トップページ
http://localhost/

商品詳細ページ
http://localhost/items/3

購入画面
http://localhost/purchase/3

届け先変更画面
http://localhost/purchase/3/address

支払い用スクリプト
https://checkout.stripe.com/c/pay/cs_test_a1P8lemeSHNCekFodM4x5JsYtfbALOuPeldcBuaddIThEFRgR7S25SKyFr#fidnandhYHdWcXxpYCc%2FJ2FgY2RwaXEnKSdicGRmZGhqaWBTZHdsZGtxJz8nZmprcXdqaScpJ2R1bE5gfCc%2FJ3VuWnFgdnFaMDRRSnJPYUZpaFxHMWxWdncyPWg8ZGN2fWJfNDddXX12Q319N01mSTZDbEZCdHFiVk1gRjJWUj1xTEJmfFFBMm1VZEYxNUk0U3E8dWxWbU43TmB%2FQ1Z%2FSnQ1NXUyNHRDS11VJyknY3dqaFZgd3Ngdyc%2FcXdwYCknZ2RmbmJ3anBrYUZqaWp3Jz8nJmNjY2NjYycpJ2lkfGpwcVF8dWAnPyd2bGtiaWBabHFgaCcpJ2BrZGdpYFVpZGZgbWppYWB3dic%2FcXdwYHgl

購入完了画面
http://localhost/checkout/success/3?session_id=cs_test_a1P8lemeSHNCekFodM4x5JsYtfbALOuPeldcBuaddIThEFRgR7S25SKyFr

マイページ（購入した商品）
http://localhost/mypage

マイページ（出品した商品）
http://localhost/mypage?page=sell

プロフィール更新画面
http://localhost/mypage/profile

  ---
  # ER図

  <img width="943" height="779" alt="Screenshot 2026-03-23 171002" src="https://github.com/user-attachments/assets/68357c36-1e48-455e-a5b5-55451dc5eea2" />

