<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tasklist;  //追加

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // 「タスク一覧表示処理」
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasklists = $user->tasklists()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasklists,
            ];
            return view('welcome', $data);
        }
        
        return view('welcome', $data);
        
        // $tasks = Task::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // 「作成ページ」
    public function create()
    {
        $tasklist = new Tasklist;
        
        if (\Auth::id() === $tasklist->user_id) {
            
            return view('tasks.create', [
                'task' => $tasklist,
            ]);
        }
        
        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:191',
        ]);
        
        $tasklist = new Tasklist;
        // $task->string('status', 10);           //追加
        // $task->status = $request->status;    // 追加
        // $task->content = $request->content;
        // $task->save();

        $request->user()->tasklists()->create([
            'status' => $request->status,
            'content' => $request->content,
        ]);
        
        return redirect('/');
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // 「詳細ページ」
    public function show($id)
    {
        $tasklist = Tasklist::find($id);
        
        if (\Auth::id() === $tasklist->user_id) {
            return view('tasks.show', [
                'task' => $tasklist,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // 「編集ページ」
    public function edit($id)
    {
        $tasklist = Tasklist::find($id);

        if (\Auth::id() === $tasklist->user_id) {
            return view('tasks.edit', [
                'task' => $tasklist,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:191',
        ]);

        $tasklist = Tasklist::find($id);
        // $tasklist->status = $request->status;    // 追加
        // $tasklist->content = $request->content;
        // $tasklist->save();

        $request->user()->tasklists()->create([
            'status' => $request->status,
            'content' => $request->content,
        ]);
        
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tasklist = \App\Tasklist::find($id);

        if (\Auth::id() === $tasklist->user_id) {
            $tasklist->delete();
        }

        return redirect('/');
        
        // $task = Task::find($id);
        // $task->delete();
        
        // return redirect('/');
    }
}
