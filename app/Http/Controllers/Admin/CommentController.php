<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Comment;

class CommentController extends Controller
{
    //
    public function index()
    {
        return view('admin/comment/index')->withComments(Comment::all());
    }

    public function edit($id)
    {
        return view('admin/comment/edit')->withComment(Comment::find($id));
    }

    public function update(Request $request)
    {
        // 数据验证
        $this->validate($request, [
            'id' => 'required', // 必填
            'nickname' => 'required', // 必填、在 articles 表中唯一、最大长度 255
            'content' => 'required', // 必填
        ]);

//        $article = new Article();
        $article          = Comment::findOrFail($request->get('id'));
        $article->nickname = $request->get('nickname');
        $article->content = $request->get('content');

        // 将数据保存到数据库，通过判断保存结果，控制页面进行不同跳转
        if ($article->save()) {
            return redirect('admin/comment'); // 保存成功，跳转到 文章管理 页
        } else {
            // 保存失败，跳回来路页面，保留用户的输入，并给出提示
            return redirect()->back()->withInput()->withErrors('保存失败！');
        }


    }

    public function destroy($id)
    {
        Comment::find($id)->delete();
        return redirect()->back()->withInput()->withErrors('删除成功！');
    }

}
