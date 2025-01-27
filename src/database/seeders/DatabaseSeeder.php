<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // `storage/app/public/shop,review` ディレクトリを削除
        File::deleteDirectory(storage_path('app/public/shop'));
        File::deleteDirectory(storage_path('app/public/review'));

        // `public/img/shop,review` を `storage/app/public/shop,review` にコピー
        File::copyDirectory(public_path('img/shop'), storage_path('app/public/shop'));
        File::copyDirectory(public_path('img/review'), storage_path('app/public/review'));

        // 各シーダークラスを呼び出し
        $this->call([
            UserSeeder::class,
            RegionSeeder::class,
            GenreSeeder::class,
            ShopSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}