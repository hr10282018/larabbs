<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;

class CategoriesController extends Controller
{
    //
    public function show(Category $category){

      //读取分类ID关联额话题，每页20条
      $topics =Topic::where('category_id',$category->id)->paginate(20);

      //传参变量话题和分类到模板
      return view('topics.index',compact('topics', 'category'));

    }
}
