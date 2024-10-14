<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;

    /**
     * コンストラクタで件名と本文を初期化
     */
    public function __construct($subject, $body)
    {
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * メールの封筒情報を定義
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject, // コンストラクタで設定された件名を使用
        );
    }

    /**
     * メールのコンテンツを定義
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-notification', // 適切なビューを設定
            with: ['body' => $this->body],  // メール本文をビューに渡す
        );
    }

    /**
     * メールの添付ファイルを定義 (添付ファイルがない場合は空配列)
     */
    public function attachments(): array
    {
        return [];
    }
}