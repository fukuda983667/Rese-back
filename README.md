# Rese-back

飲食店の予約管理SPAアプリです。
※フロントとバック両方の環境構築が必要です。

## 前提条件
- Dockerがインストールされていること
- Docker Composeがインストールされていること

## 環境構築

1. リポジトリをクローンしたい任意のディレクトリで以下のコマンドを実行してください。

    ```bash
    git clone https://github.com/fukuda983667/Rese-back
    ```

2. クローンしたRese-backディレクトリに移動

    ```bash
    cd Rese-back
    ```

3. phpコンテナにログイン→`composer`をインストールします。

    ```bash
    docker-compose exec php bash
    ```
    ```
    composer install
    ```

4. `.env.example`ファイルをコピーして`.env`ファイルを作成します。

    ```bash
    cp .env.example .env
    ```

5. `.env`ファイルを編集し、必要な環境変数を設定します（11～16行目）。

   ```
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=laravel_db
   DB_USERNAME=laravel_user
   DB_PASSWORD=laravel_pass
   ```

6. アプリケーションキーを生成します。

    ```bash
    php artisan key:generate
    ```

7. データベースのマイグレーションを実行します。

    ```bash
    php artisan migrate
    ```

9. データベースのシーディングを実行します。

    ```bash
    php artisan db:seed
    ```

## 仕様技術(実行環境)

- PHP : 8.1.18
- Laravel : 10.48.17
- MySQL : 8.0.32
- NGINX : 1.26.1
- docker-compose.yml : 3.8

## ER図

![ER図](/img/ER.svg)
