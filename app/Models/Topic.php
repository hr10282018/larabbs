<?php

namespace App\Models;

class Topic extends Model
{
  protected $fillable = [
    'title', 'body', 'category_id', 'excerpt', 'slug'
  ];

    public function category()
    {
        return $this->belongsTo(Category::class); //一个话题属于一个分类
    }
    public function user()
    {
        return $this->belongsTo(User::class); //一个话题属于一个作者
    }
    public function replies(){
      return $this->hasMany(Reply::class);    // 一个话题有多条回复
    }


    // 话题列表排序方法
    public function scopeWithOrder($query,$order){
      // 不同的排序，使用不同的数据读取逻辑
      switch ($order) {
        case 'recent':
            $query->recent();
            break;

        default:
            $query->recentReplied();
            break;
      }
    }
    /*
      方法名有scope前缀，定义了作用域，则可以在查询模型时调用作用域方法
       在调用时，不需加上scope前缀，如上面的recent()和recentReplied()
    */
    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

    // 设置slug路由，供控制器（TopicsController）方法（成功创建话题）中的路由跳转使用
    public function link($params = [])  // 参数 $params允许附加URL参数的设定。
    {
      //用户id+slug 路由，如 http://larabbs.test/topics/115/slug-translation-test
      return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }


}
