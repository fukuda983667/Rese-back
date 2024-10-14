<?php

namespace App\Http\Controllers;

use App\Mail\AdminNotificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function getVendors() {
        // roleが'vendor'のユーザーを取得
        $vendors = User::where('role', 'vendor')->get();

        return response()->json([
            'vendors' => $vendors,
        ], 200);
    }



    public function sendEmail(Request $request)
    {
        // リクエストデータのバリデーション
        $request->validate([
            'email_recipient' => 'required|string',
            'email_subject' => 'required|string',
            'email_body' => 'required|string',
        ]);

        // メール送信対象のユーザーを取得
        if ($request->email_recipient === 'all-users') {
            $recipients = User::where('role', 'user')->get();  // roleが'user'のユーザー
        } elseif ($request->email_recipient === 'all-vendors') {
            $recipients = User::where('role', 'vendor')->get();  // roleが'vendor'のユーザー
        } else {
            return response()->json(['message' => '不正な宛先です。'], 400);
        }

        // メールを送信
        $count = 0;
        foreach ($recipients as $recipient) {
            Mail::to($recipient->email)->send(new AdminNotificationMail(
                $request->email_subject,
                $request->email_body
            ));

            $count++;

            // 4通ごとに10秒待機
            if ($count % 4 == 0) {
                sleep(10); // 10秒待機
            }
        }

        return response()->json(['message' => 'メールが送信されました。'], 200);
    }
}
