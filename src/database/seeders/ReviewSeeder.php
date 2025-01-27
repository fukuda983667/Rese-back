<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Review;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shopId = 1; // ここではIDが1の店舗に対してレビューを作成する例

        // ユーザーをランダムに選んで、レビューを作成
        User::factory()->count(10)->create()->each(function ($user) use ($shopId) {
            Review::create([
                'user_id' => $user->id,
                'shop_id' => $shopId,
                'image_url' => $this->getRandomImageUrl(),
                'review_text' => $this->getRandomReviewText(),
                'rating' => rand(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    // ランダムな画像URL（nullまたは'sushi.png'）を生成するメソッド
    private function getRandomImageUrl(): ?string
    {
        // 50%の確率でnullまたは画像を設定
        return rand(0, 1) ? 'sushi.png' : null;
    }

    // ランダムな日本語のレビュー内容を生成するメソッド
    private function getRandomReviewText(): string
    {
        $reviews = [
            'とても良いサービスで、また来たいと思いました。',
            '雰囲気が良くて、料理もおいしかったです。',
            'スタッフの対応が素晴らしく、楽しい時間を過ごせました。',
            '料理の味は良かったですが、少し高かったです。',
            '店内が落ち着いた雰囲気で、ゆっくりできました。',
            'おすすめの料理を教えてもらって、とてもおいしかったです。',
            'とても満足しましたが、次回はもう少し早く来たいです。',
            'サービスが少し遅かったですが、味は最高でした。',
            '店員さんが親切で、居心地が良かったです。',
            'ランチタイムに行ったのですが、早く提供されて助かりました。',
        ];

        // ランダムにレビューを選ぶ
        return $reviews[array_rand($reviews)];
    }
}