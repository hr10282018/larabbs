<?php

namespace App\Observers;

use App\Models\Topic;
//use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored
//上面的事件让你每当有特定的模型类在数据库保存或更新时，执行代码
/*
  1当一个新模型被初次保存将会触发 creating 以及 created 事件
  2如果一个模型已经存在于数据库且调用了 save 方法，将会触发 updating 和 updated 事件
  在这两种情况下都会触发 saving 和 saved 事件
  这些方法只接收一个model参数
*/

class TopicObserver
{

    public function saving(Topic $topic){   //接收Topic模型参数

      $topic->body = clean($topic->body, 'user_topic_body');  //clean()-xxs攻击过滤html和js（config/purifier.php）

      // excerpt是话题的摘录字段。摘录由文章内容自动生成，需要在话题数据存入数据库之前生成。
      $topic->excerpt = make_excerpt($topic->body);// make_excerpt-自定义的辅助方法（app\helpers.php文件）：

       // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
      /* if ( ! $topic->slug) {
        // app-允许我们使用Laravel服务容器，用来生成SlugTranslateHandler实例。
        //$topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);  // app/Handlers/SlugTranslateHandler.php


        //将上面slug翻译调用改为队列执行的方式
        //dispatch()-推送任务队列

        dispatch(new TranslateSlug($topic));
      } */
    }

    /*
      不能在saving推送任务，因为传参$topic此时还未在数据库创建，id为null
      所以需要在saved中推送任务
    */
    public function saved(Topic $topic)
    {
        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if ( ! $topic->slug) {

            // 推送任务到队列
            dispatch(new TranslateSlug($topic));
        }
    }
}
