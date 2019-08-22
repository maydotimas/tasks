<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // gets all the tasks record
    public function get_all()
    {
        $tasks = Task::all();
        return $tasks;
    }

    // stores a new task record
    public function store_task(StoreTaskRequest $request)
    {
        // store tasks
        $task = Task::create($request->all());
        // if store is successful
        if ($task) {
            return response()->json('created');
        } else { // if store failed
            return response()->json('failed');
        }
    }

    // updates a task
    public function update_task(Request $request)
    {
        // find tasks record
        $task = Task::find($request->id);
        // if task is existing
        if ($task) {
            // update
            Task::find($request->id)
                ->update($request->all());

            return response()->json('updated');

        } else { // if task does not exist
            return response()->json('failed');
        }
    }

    // completes a task
    public function complete_task(Request $request)
    {
        // find tasks record
        $task = Task::find($request->id);
        // if task is existing
        if ($task) {
            // if not completed, then mark as completed
            if($task->is_done==false){
                // update
                Task::find($request->id)
                    ->update(['is_done' => true]);
            }else{
                // update
                Task::find($request->id)
                    ->update(['is_done' => false]);
            }


            return response()->json('completed');

        } else { // if task does not exist
            return response()->json('failed');
        }
    }

    // deletes a task record
    public function delete_task($id)
    {
        // find tasks record
        $task = Task::find($id);
        // if task is existing
        if ($task) {
            // delete
            Task::destroy($id);
            return response()->json('deleted');

        } else { // if task does not exist

            return response()->json('failed');
        }
    }
}
