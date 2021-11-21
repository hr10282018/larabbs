<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{

    // 删除回复策略-回复的作者与回复的话题的作者可以删除
    public function destroy(User $user, Reply $reply)
    {
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
