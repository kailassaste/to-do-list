<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\MstStatus;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::with('status')->get(); 
        $mst_status = MstStatus::all(); 

        if ($request->ajax()) {
            return response()->json(['tasks' => $tasks]);
        }

        return view('task.index', compact('tasks', 'mst_status'));
    }

    public function create()
    {
        $mst_status = MstStatus::all();

        return view('task.create', compact('mst_status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'startDate' => 'required|date',
            'dueDate' => 'required|date',
            'status_id' => 'required|exists:mst_status,id',
        ]);

        try {
            $task = Task::create($request->all());
    
            if ($request->ajax()) 
            {
                return response()->json($task);
            }
            return redirect()->route('task.index');
        } catch (\Exception $e) 
        {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $mst_status = MstStatus::all();

        return view('task.edit', compact('task', 'mst_status'));
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'startDate' => 'required|date',
            'dueDate' => 'required|date',
            'status_id' => 'required|exists:mst_status,id'
        ]);

        $task->update($request->all());

        if ($request->ajax()) 
        {
            return response()->json($task);
        }

        return redirect()->route('task.index');
    }

    public function destroy($id)
    {
       $task = Task::findOrFail($id);
       $task->delete();

       if (request()->ajax()) {
        return response()->json(['message' => 'Task deleted successfully']);
        }

        return redirect()->route('task.index');
    }
}
