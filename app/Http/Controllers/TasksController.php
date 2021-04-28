<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        //
        $data = [];
        if (\Auth::check()) {
        $user = \Auth::user();
            
        //$tasks = Task::all();
        
        $tasks = $user -> tasks()->orderBy('created_at','desc')->paginate(10);
        
        $data =[
            'user' => $user,
            'tasks' => $tasks,
            ];
        
        return view('tasks.index',[
            "tasks" => $tasks,
            ]);
        }
    }

    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;
        
        if (\Auth::check()) {
        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);}else{
        return redirect('/');
        }
    }
    

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status'  => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        // タスクを作成
        $task = new Task;
        $task->status  = $request->status;
        $task->content = $request->content;
        $task->user_id = $request->user()->id;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('tasks');
    }

    // getでtasks/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        //dd($task);
        if (\Auth::id() === $task->user_id) {
            // 自分のタスク
            return view('tasks.show', [
                'task' => $task,
            ]);
        
        } else {
            // 他人のタスク
            return redirect('/');
        }

        // メッセージ詳細ビューでそれを表示
        // return view('tasks.show', [
        //     'task' => $task,
        // ]);
    }

    // getでtasks/（任意のid）/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        // 追記
        if (\Auth::id() === $task->user_id) {
            // 自分のタスク
            // メッセージ編集ビューでそれを表示
            return view('tasks.edit', [
                'task' => $task,
        ]);
        }else{
            // 他人のタスク
            return redirect('/');
        }
        
    }

    // putまたはpatchでtasks/（任意のid）にアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            // メッセージを更新
            $task->status  = $request->status;
            $task->content = $request->content;
            $task->user_id = $request->user()->id;
            $task->save();
            
            return redirect('tasks');
        }
        
        // トップページへリダイレクトさせる
        //return back();
        return redirect('/');
    }

    // deleteでtasks/（任意のid）にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        // 追記
        if (\Auth::id() === $task->user_id) {
            // 自分のタスク
            // メッセージを削除
            $task->delete();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}