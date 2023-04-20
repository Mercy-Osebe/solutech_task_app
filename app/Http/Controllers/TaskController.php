<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\UserTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return response(['tasks' => $tasks], 200);
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
            "name" => ['required', 'min:6'],
            "description" => ['required', 'max:255'],
            "status_id" => ['required'],
            "remarks" => ['required'],
            "due_date"=>['required']

        ]);



        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        $task->status_id = $request->status_id;
        $task->save();


        if ($task) {
            $userTask = new UserTask();
            $userTask->user_id = Auth::user()->id;
            $userTask->task_id = $task->id;
            $userTask->due_date = $request->due_date;
            $userTask->end_time = $request->end_time;
            $userTask->remarks = $request->remarks;
            $userTask->status_id = $request->status_id;
            $userTask->save();

            return response([
                'task' => $task,
                'message' => 'Task saved successfully'
            ], 201);
        } else {
            return response([
                'message' => 'something went wrong try again later'
            ], 500);
        }
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
        $task = Task::findOrFail($id);
        return response([
            "task" => $task
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
        $task = Task::findOrFail($id);

        $request->validate([
            "name" => ['required', 'min:6'],
            "description" => ['required', 'max:255'],
            "status_id" => ['required'],
            "remarks" => ['required'],
            "due_date"=>['required']

        ]);

        $task->name = $request->name;
        $task->description = $request->description;
        $task->status_id = $request->status_id;
        $task->update();
        if ($task) {
            $userTask = UserTask::where('task_id',$task->id)->get();
            $userTask->user_id = Auth::user()->id;
            $userTask->task_id = $task->id;
            $userTask->due_date = $request->due_date;
            $userTask->end_time = $request->end_time;
            $userTask->remarks = $request->remarks;
            $userTask->status_id = $request->status_id;
            $userTask->update();




            return response([
                'task' => $task,
                'message' => 'Task updated successfully'
            ], 201);
        } else {
            return response([
                'message' => 'something went wrong try again later'
            ], 500);
        }
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
        $task = Task::findOrFail($id);
        $task->delete();
        return response([
            "message" => 'task deleted successfully'
        ], 200);
    }
}
