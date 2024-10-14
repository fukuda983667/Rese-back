<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'テスト太郎',
                'email' => 'test-taro@mail.com',
                'password' => Hash::make('test-taro'),
                'role' => 'user',
                'created_at' => Carbon::create(2024, 9, 30), // 2024/09/30を設定
                'updated_at' => Carbon::create(2024, 9, 30), // 2024/09/30を設定
            ],
            [
                'name' => '管理者太郎',
                'email' => 'admin-taro@mail.com',
                'password' => Hash::make('admin-taro'),
                'role' => 'admin',
                'created_at' => Carbon::create(2024, 9, 30),
                'updated_at' => Carbon::create(2024, 9, 30),
            ],
            [
                'name' => 'ベンダー1',
                'email' => 'vendor1@mail.com',
                'password' => Hash::make('vendor-password1'),
                'role' => 'vendor',
                'created_at' => Carbon::create(2024, 9, 30), // 2024/09/30を設定
                'updated_at' => Carbon::create(2024, 9, 30), // 2024/09/30を設定
            ],
            [
                'name' => 'ベンダー2',
                'email' => 'vendor2@mail.com',
                'password' => Hash::make('vendor-password2'),
                'role' => 'vendor',
                'created_at' => Carbon::create(2024, 9, 30),
                'updated_at' => Carbon::create(2024, 9, 30),
            ],
            [
                'name' => 'ベンダー3',
                'email' => 'vendor3@mail.com',
                'password' => Hash::make('vendor-password3'),
                'role' => 'vendor',
                'created_at' => Carbon::create(2024, 9, 30),
                'updated_at' => Carbon::create(2024, 9, 30),
            ],
            [
                'name' => 'ベンダー4',
                'email' => 'vendor4@mail.com',
                'password' => Hash::make('vendor-password4'),
                'role' => 'vendor',
                'created_at' => Carbon::create(2024, 9, 30),
                'updated_at' => Carbon::create(2024, 9, 30),
            ],
            [
                'name' => 'ベンダー5',
                'email' => 'vendor5@mail.com',
                'password' => Hash::make('vendor-password5'),
                'role' => 'vendor',
                'created_at' => Carbon::create(2024, 9, 30),
                'updated_at' => Carbon::create(2024, 9, 30),
            ],
            [
                'name' => 'ベンダー6',
                'email' => 'vendor6@mail.com',
                'password' => Hash::make('vendor-password6'),
                'role' => 'vendor',
                'created_at' => Carbon::create(2024, 9, 30),
                'updated_at' => Carbon::create(2024, 9, 30),
            ],
            [
                'name' => 'ベンダー7',
                'email' => 'vendor7@mail.com',
                'password' => Hash::make('vendor-password7'),
                'role' => 'vendor',
                'created_at' => Carbon::create(2024, 9, 30),
                'updated_at' => Carbon::create(2024, 9, 30),
            ],
            [
                'name' => 'ベンダー8',
                'email' => 'vendor8@mail.com',
                'password' => Hash::make('vendor-password8'),
                'role' => 'vendor',
                'created_at' => Carbon::create(2024, 9, 30),
                'updated_at' => Carbon::create(2024, 9, 30),
            ],
            [
                'name' => 'ベンダー9',
                'email' => 'vendor9@mail.com',
                'password' => Hash::make('vendor-password9'),
                'role' => 'vendor',
                'created_at' => Carbon::create(2024, 9, 30),
                'updated_at' => Carbon::create(2024, 9, 30),
            ],
            [
                'name' => 'ベンダー10',
                'email' => 'vendor10@mail.com',
                'password' => Hash::make('vendor-password10'),
                'role' => 'vendor',
                'created_at' => Carbon::create(2024, 9, 30),
                'updated_at' => Carbon::create(2024, 9, 30),
            ],
            // 追加のユーザーデータをここに記述
        ]);
    }
}
