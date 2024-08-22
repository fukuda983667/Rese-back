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

3. Docker Composeを使用してコンテナを作成・起動します。※Docker Descktop起動時に実行してください。

    ```bash
    docker-compose up -d --build
    ```

4. phpコンテナにログイン→`composer`をインストールします。

    ```bash
    docker-compose exec php bash
    ```
    ```
    composer install
    ```

5. `.env.example`ファイルをコピーして`.env`ファイルを作成します。

    ```bash
    cp .env.example .env
    ```

6. `.env`ファイルを編集し、必要な環境変数を設定します（11～16行目）。

   ```
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=laravel_db
   DB_USERNAME=laravel_user
   DB_PASSWORD=laravel_pass
   ```

7. アプリケーションキーを生成します。

    ```bash
    php artisan key:generate
    ```

8. データベースのマイグレーションを実行します。

    ```bash
    php artisan migrate
    ```

9. データベースのシーディングを実行します。

    ```bash
    php artisan db:seed
    ```

10. アプリケーションがhttp://localhost:3000 で利用可能になります。
   ※Rese-frontの環境構築が必要です。

## 仕様技術(実行環境)

- PHP : 8.1.18
- Laravel : 10.48.17
- MySQL : 8.0.32
- NGINX : 1.26.1
- docker-compose.yml : 3.8

## ER図

![ER図](/img/ER.svg)
