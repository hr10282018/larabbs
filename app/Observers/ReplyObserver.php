<?php

namespace App\Observers;

use App\Models\Reply;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
  // 当 Elequont 模型数据成功创建时，created 方法将会被调用
  public function created(Reply $reply)
  {
    // $reply->topic->increment('reply_count', 1);   // +1（回复数量）

    /*
      字段缓存的方式
      创建成功后计算本话题下评论总数，然后再对其 reply_count 字段进行赋值
    */
    $reply->topic->reply_count = $reply->topic->replies->count();
    $reply->topic->save();
  }

  // 在用户回复数据存储之前触发
  public function creating(Reply $reply)
  {
    // 处理xxs，过滤用户回复的数据
    $reply->content = clean($reply->content, 'user_topic_body');  // user_topic_body-过滤规则
  }


}
