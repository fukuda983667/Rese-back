<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // `storage/app/public/shop` ディレクトリを削除
        File::deleteDirectory(storage_path('app/public/shop'));

        // `public/img/shop` を `storage/app/public/shop` にコピー
        File::copyDirectory(public_path('img/shop'), storage_path('app/public/shop'));

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