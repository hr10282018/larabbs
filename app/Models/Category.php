<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /*  使用 public $timestamps = false; 进行设置，告知Laravel此模型在创建和更新时
        不需维护created_at和 updated_at 这两个字段。
    */
    public $timestamps = false;

    protected $fillable = [     //允许修改的字段
      'name', 'description',
    ];

    

}
