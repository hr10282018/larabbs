<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue; //Laravel会检测ShouldQueue接口并自动将通知的发送放入队列中
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reply;

class TopicReplied extends Notification implements ShouldQueue // 使用队列，实现该接口
{
    use Queueable;

    public $reply;

    public function __construct(Reply $reply)
    {
        // 注入回复实体，方便 toDatabase 方法中的使用
        $this->reply = $reply;
    }

    // 每个通知类都有个 via() 方法，它决定了通知在哪个频道上发送。
    public function via($notifiable)
    {
        // 开启通知的频道, 我们用database数据库来作为通知频道
        return ['database','mail'];   // 加上邮件
    }


    /*
      因为使用数据库通知频道，我们需要定义toDatabase()。
      此方法接收 $notifiable 实例参数并返回一个普通的 PHP 数组。
      这个返回的数组将被转成 JSON 格式并存储到通知数据表的 data 字段中。
    */
    public function toDatabase($notifiable)
    {
        $topic = $this->reply->topic;
        $link =  $topic->link(['#reply' . $this->reply->id]);   // URL参数 #reply+回复数据id

        // 存入数据库里的数据
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
        ];
    }

    // 定义邮件通知的内容
    public function toMail($notifiable)
    {
        $url = $this->reply->topic->link(['#reply' . $this->reply->id]);

        return (new MailMessage)
                    ->line('你的话题有新回复！')    // 邮件标题
                    ->action('查看回复', $url);     // 邮件内容，附带链接
    }
}
