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
                'email_verified_at' => now(),
                'password' => Hash::make('test-taro'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'テスト花子',
                'email' => 'test-hanako@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('test-hanako'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '管理者太郎',
                'email' => 'admin-taro@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin-taro'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ベンダー1',
                'email' => 'vendor1@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('vendor-password1'),
                'role' => 'vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ベンダー2',
                'email' => 'vendor2@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('vendor-password2'),
                'role' => 'vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ベンダー3',
                'email' => 'vendor3@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('vendor-password3'),
                'role' => 'vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ベンダー4',
                'email' => 'vendor4@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('vendor-password4'),
                'role' => 'vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ベンダー5',
                'email' => 'vendor5@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('vendor-password5'),
                'role' => 'vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ベンダー6',
                'email' => 'vendor6@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('vendor-password6'),
                'role' => 'vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ベンダー7',
                'email' => 'vendor7@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('vendor-password7'),
                'role' => 'vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ベンダー8',
                'email' => 'vendor8@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('vendor-password8'),
                'role' => 'vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ベンダー9',
                'email' => 'vendor9@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('vendor-password9'),
                'role' => 'vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ベンダー10',
                'email' => 'vendor10@mail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('vendor-password10'),
                'role' => 'vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 追加のユーザーデータをここに記述
        ]);
    }
}
