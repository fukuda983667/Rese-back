<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reviews')->insert([
            [
                'user_id' => 1,
                'shop_id' => 1,
                'review_title' => '素晴らしいサービス',
                'review_text' => 'サービスが素晴らしく、料理も最高でした！',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'shop_id' => 1,
                'review_title' => '良い体験',
                'review_text' => '楽しい時間を過ごせましたが、改善の余地もあります。',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'shop_id' => 1,
                'review_title' => '素晴らしいサービス',
                'review_text' => 'サービスが素晴らしく、料理も最高でした！',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'shop_id' => 1,
                'review_title' => '良い体験',
                'review_text' => '楽しい時間を過ごせましたが、改善の余地もあります。',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],            [
                'user_id' => 1,
                'shop_id' => 1,
                'review_title' => '素晴らしいサービス',
                'review_text' => 'サービスが素晴らしく、料理も最高でした！',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'shop_id' => 1,
                'review_title' => '良い体験',
                'review_text' => '楽しい時間を過ごせましたが、改善の余地もあります。',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],            [
                'user_id' => 1,
                'shop_id' => 1,
                'review_title' => '素晴らしいサービス',
                'review_text' => 'サービスが素晴らしく、料理も最高でした！',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'shop_id' => 1,
                'review_title' => '良い体験',
                'review_text' => '楽しい時間を過ごせましたが、改善の余地もあります。',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}