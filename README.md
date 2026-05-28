# laravel-docker-template

# coachtech フリマアプリ

フリマアプリ作成

環境構築
cd coachtech/laravel

git clone git@github.com:Estra-Coachtech/laravel-docker-template.git

mv laravel-docker-template flea-market

GutHubで新しいリモートリポジトリ作成「flea-market」

cd flea-market

git remote set-url origin git@github.com:yaching04/flea-market.git

git remote -v

git add .

git commit -m "リモートリポジトリの変更"

git push origin main

Dockerの設定

docker-compose up -d --build

Laravel のパッケージのインストール

docker-compose exec php bash

composer install

.envファイルの作成・修正

cp .env.example .env

マイグレーション作成

php artisan make:model Condition -m

php artisan make:model Category -m

# 2. メインのテーブル（コントローラー必要）

php artisan make:model Item -mcr

# 3. 関連テーブル（コントローラー不要なものが多い）

php artisan make:model Like -m

php artisan make:model Comment -m

php artisan make:model Purchase -mcr

php artisan make:model ItemCategory -m

# 4. users拡張（マイグレーションのみ）

php artisan make:migration add_custom_columns_to_users_table --table=users

開発環境

PHP 8.1

Laravel 10.x

MySQL 8.0

Docker + Docker Compose

Stripe（決済テスト）
