<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tasks = Task::all();
        return response(['task' => $tasks], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
           "name"=>['required','min:6'],
           "description"=>['required','max:255'],
           "status_id"=>['required']

        ]);

        $task=new Task();
        $task->name=$request->name;
        $task->description=$request->description;
        $task->status_id=$request->status_id;

        $task->save();
        return response([
            'task'=>$task,
            'message'=>'Task saved successfully'
        ],201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $task=Task::findOrFail($id);
        return response([
            "task"=>$task
        ]);
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
        //
        $task=Task::findOrFail($id);

        $request->validate([
            "name"=>['required','min:6'],
            "description"=>['required','max:255'],
            "status_id"=>['required']
 
         ]);
 
         $task->name=$request->name;
         $task->description=$request->description;
         $task->status_id=$request->status_id;
 
         $task->update();
         return response([
             'task'=>$task,
             'message'=>'Task updated successfully'
         ],201);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $task=Task::findOrFail($id);
        $task->delete();
        return response([
            "message"=>'task deleted successfully'
        ],200);
    }
}
