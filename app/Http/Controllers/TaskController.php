<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return view('tasks.index');
    }

    public function all()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required|unique:tasks,task',
        ]);

        $task = Task::create([
            'task' => $request->task,
            'completed' => false
        ]);

        return response()->json($task);
    }


    public function update(Request $request, Task $task)
    {
        $task->completed = !$task->completed;
        $task->save();

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['success' => true]);
    }
}

