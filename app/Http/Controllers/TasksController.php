<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    // getでtasks/にアクセスされた場合の一覧表示
    public function index()
    {
        /*
        $tasks = Task::all();
        
        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
        */
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            
            $tasks = $user->tasks()->orderBy('created_at')->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        return view('welcome', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    // getでtasks/create/にアクセスされた場合の新規登録画面表示
    public function create()
    {
        $task = new Task;
        
        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
    // postでtasks/にアクセスされた場合の新規登録処理
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        // 認証済みユーザ（閲覧者)の投稿として作成(リクエストされた値をもとに作成)
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);
        /*
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        */
        
        // トップページへリダイレクト
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    // getでtasks/任意のidにアクセスされた場合の取得表示
    public function show($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // タスク詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    // getでtasks/任意のidにアクセスされた場合の更新画面表示
    public function edit($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        // タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    // put or patchでtasks/任意のidにアクセスされた場合の更新処理
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        $task = Task::findOrFail($id);
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        //　トップページへリダイレクト
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    // deleteでtasks/任意のidにアクセスされた場合の削除処理
    public function destroy($id)
    {
        // idの値でタスクを取得
        $task = \App\Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        
        // トップページへリダイレクト
        return redirect('/');
    }
}
