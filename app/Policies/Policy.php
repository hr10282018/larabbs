<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    // before()-会在策略中其它所有方法之前执行，这样提供了一种全局授权的方案
    public function before($user, $ability)
    {
        // 如果用户拥有管理内容的权限的话，即授权通过
        if ($user->can('manage_contents')) {
            return true;
        }
    }
}
