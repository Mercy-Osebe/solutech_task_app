<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\UserTask;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $userId = Auth::id();
        $tasks = Task::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
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
            "due_date" => ['required']

        ]);
        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        $task->status_id = $request->status_id;
        $task->due_date = $request->due_date;
        $task->save();


        if ($task) {
            $userTask = new UserTask();
            $userTask->user_id = Auth::user()->id;
            $userTask->task_id = $task->id;
            $userTask->remarks = $request->remarks;
            $userTask->status_id = $request->status_id;

            $userTask->due_date = $task->due_date;
            if ($request->status_id == 1) {
                $userTask->start_time = date('Y-m-d H:i:s');;
            } else if ($request->status_id == 3) {
                $userTask->end_time = date('Y-m-d H:i:s');;
            }


            $userTask->save();

            return response([
                'task' => $task,
                'message' => 'Task saved successfully',
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
        $userTask = UserTask::where('task_id', $task->id)->firstOrFail();

        $start_time = new DateTime($userTask->start_time);
        $end_time = new DateTime($userTask->due_time);
        $interval = $start_time->diff($end_time);
        $duration = $interval->format('%Y-%m-%d %H:%I:%S');
        return response([
            "task" => $task,
            'userTask' => $userTask,
            'taskDuration' => $duration,
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

        ]);

        $task->name = $request->name;
        $task->description = $request->description;
        $task->status_id = $request->status_id;
        $task->update();

        if ($task) {
            $userTask = UserTask::where('task_id', $task->id)->firstOrFail();
            $userTask->user_id = Auth::user()->id;
            $userTask->task_id = $task->id;
            $userTask->remarks = $request->remarks;
            $userTask->status_id = $request->status_id;
            if ($request->status_id == 1) {
                $userTask->start_time = date('Y-m-d H:i:s');;
            } else if ($request->status_id == 3) {
                $userTask->end_time = date('Y-m-d H:i:s');;
            }
            $userTask->update();

            return response([
                'task' => $task,
                'message' => 'Task updated successfully',
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
            'tasks' => Task::all(),
            "message" => 'task deleted successfully'
        ], 200);
    }
}
