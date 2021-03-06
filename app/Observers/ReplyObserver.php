<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
  // 当 Elequont 模型数据成功创建时，created 方法将会被调用
  public function created(Reply $reply)
  {
    // 命令行运行迁移时不做这些操作！
    if ( ! app()->runningInConsole()) {
      // $reply->topic->increment('reply_count', 1);   // +1（回复数量）

    /*
      字段缓存的方式
      创建成功后计算本话题下评论总数，然后再对其 reply_count 字段进行赋值
    */
    //$reply->topic->reply_count = $reply->topic->replies->count();
    //$reply->topic->save();

    $reply->topic->updateReplyCount();  // 代替上面两句(此方法在Topic模型文件中定义)

    /*
      通知话题作者有新的评论
      notify()-默认的User模型中使用了trait—Notifiable，它包含着一个可以用来发通知的方法notify()，接收一个通知实例做参数
      提示：此方法我们能在Use模型中对它进行重写。
    */
    $reply->topic->user->notify(new TopicReplied($reply));  // 传入通知实例做参数。(通知类定义在App\Notifications\TopicReplied.php)
    }

  }

  // 在用户回复数据存储之前触发
  public function creating(Reply $reply)
  {
    // 处理xxs，过滤用户回复的数据
    $reply->content = clean($reply->content, 'user_topic_body');  // user_topic_body-过滤规则
  }

  // 在用户删除回复后触发
  public function deleted(Reply $reply)
  {
    // $reply->topic->reply_count = $reply->topic->replies->count();
    // $reply->topic->save();

    // updateReplyCount()-在Topic模型定义的此方法，为了减少重复性代码，将其抽象化出来
    $reply->topic->updateReplyCount(); // 用这句代替上面两句
  }

}
