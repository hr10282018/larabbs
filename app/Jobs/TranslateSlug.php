<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;      //序列化和反序列化

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;  // 引入slug接口翻译类，用它的方法进入定义队列任务

class TranslateSlug implements ShouldQueue    // 实现队列接口
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $topic;

    public function __construct(Topic $topic) // 用来初始化一些 handle() 方法需要用到的参数。
    {
        // 队列任务构造器中接收了 Eloquent 模型，将会只序列化模型的 ID
        $this->topic = $topic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()    // 该方法会在队列任务执行时被调用
    {
        // 请求百度 API 接口进行翻译
        // app()-返回服务容器实例，自动帮你注入类的依赖（依赖注入-通过以构造函数参数，设值方法或属性字段等方式将具体组件传递给依赖方）
        $slug = app(SlugTranslateHandler::class)->translate($this->topic->title);

        /*
          因为我们会在 Observers\TopicObserver.php中的saving方法推送此任务到队列，
          如果使用$topic模型实例去对数据库进行操作，将会再次触发saving方法(具体看模型观察器的几个方法说明)，
          然后陷入调用死循环——模型监控器分发任务，任务触发模型监控器，模型监控器再次分发任务。

          所以任务中要避免使用Eloquent模型接口调用，如：create(), update(), save() 等操作
          为了避免模型监控器死循环调用，我们使用DB类直接对数据库进行操作，不去使用$topic模型实例
        */
        \DB::table('topics')->where('id', $this->topic->id)->update(['slug' => $slug]);
    }
}
