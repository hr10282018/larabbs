<?php

namespace App\Models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Cache;
use DB;
use Arr;

trait ActiveUserHelper
{
    // 用于存放临时用户数据
    protected $users = [];

    // 配置信息
    protected $topic_weight = 4; // 话题权重
    protected $reply_weight = 1; // 回复权重
    protected $pass_days = 7;    // 多少天内发表过内容
    protected $user_number = 6; // 取出来多少用户

    // 缓存相关配置
    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire_in_seconds = 65 * 60;

    // 从缓存获取活跃用户
    public function getActiveUsers()
    {
        // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
        // 否则运行匿名函数中的代码来取出活跃用户数据，返回的同时做了缓存。
        return Cache::remember($this->cache_key, $this->cache_expire_in_seconds, function(){
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        // 取得活跃用户列表
        $active_users = $this->calculateActiveUsers();
        // 并加以缓存
        $this->cacheActiveUsers($active_users);
    }

    // 获取活跃的前x名用户
    private function calculateActiveUsers()
    {
        $this->calculateTopicScore();   // 先计算话题得分,存入用户得分的二位数组
        $this->calculateReplyScore();   // 再计算回复得分，用户得分数组中有该用户则直接加分，没有则添加用户再赋值分

        // 数组按照得分排序(升序)
        $users = Arr::sort($this->users, function ($user) {
            return $user['score'];
        });

        /*
          array_reverse()-将数组倒置，第二个参数为保持数组的KEY不变
          将用户得分倒置(倒序)
        */
        $users = array_reverse($users, true);

        /*
          array_slice()-从$users(参数1)数组中取出索引0到6(参数2和3)数据，参数4的true表示保留键名顺序
        */
        $users = array_slice($users, 0, $this->user_number, true);

        // 新建一个空集合
        $active_users = collect();

        foreach ($users as $user_id => $user) {
            // 找寻下是否可以找到用户
            $user = $this->find($user_id);

            // 如果数据库里有该用户的话
            if ($user) {

                // 将此用户实体放入集合的末尾
                $active_users->push($user);
            }
        }

        // 返回数据
        return $active_users;
    }

    // 计算话题得分
    private function calculateTopicScore()
    {
        // 从话题数据表里取出限定时间范围（$pass_days）内，有发表过话题的用户
        // 并且同时取出用户此段时间内发布话题的数量

        $topic_users = Topic::query()->select(DB::raw('user_id, count(*) as topic_count'))
        /*
         Carbon::now()-获取当前时间
         Carbon::now()->subDays(x)-距离当前时间减去x天
         */
                                     ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
                                     ->groupBy('user_id')
                                     ->get();
        // 根据话题数量计算得分
        foreach ($topic_users as $value) {
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;
        }
    }

    // 计算回复得分
    private function calculateReplyScore()
    {
        // 从回复数据表里取出限定时间范围（$pass_days）内，有发表过回复的用户
        // 并且同时取出用户此段时间内发布回复的数量
        $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
                                     ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
                                     ->groupBy('user_id')
                                     ->get();
        // 根据回复数量计算得分
        foreach ($reply_users as $value) {
            $reply_score = $value->reply_count * $this->reply_weight;
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $reply_score;
            } else {
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }

    // 缓存数据
    private function cacheActiveUsers($active_users)
    {
        // 将数据放入缓存中
        // put()-参数1是键名，参数2是键值，参数3是过期时间
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_seconds);
    }
}
