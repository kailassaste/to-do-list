<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\MstStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::with('status')->where('createdBy',auth()->user()->id)->get(); 
        $mst_status = MstStatus::all(); 

        if ($request->ajax()) {
            return response()->json(['tasks' => $tasks])->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
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
        $validator = Validator::make(
        $request->all(), 
        [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'startDate' => 'required|date',
            'dueDate' => 'required|date',
            'status_id' => 'required|exists:mst_status,id',
        ],
        [
            'title.required' => 'Title field is required',
            'description.required' => 'Description field is required',
            'startDate.required' => 'StartDate field is required',
            'dueDate.required' => 'DueDate field is required',
            'status_id.exists' => 'The selected status is invalid.',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->toArray()], 422);

        }

        if($validator->passes()){
            try {
                $task = Task::create($request->all());

                $task->createdBy = auth()->id();

                $task->save();
        
                if ($request->ajax()) 
                {
                    return response()->json(['success'=>'Added new records.', 'task' => $task]);
                }
                return redirect()->route('task.index');
            } catch (\Exception $e) 
            {
                return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
            }
        }
        return response()->json(['error' => $validator->errors()->all()]); 
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

        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'startDate' => 'required|date',
                'dueDate' => 'required|date',
                'status_id' => 'required|exists:mst_status,id',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()->toArray()], 422);

        }

        if($validator->passes()){
            try {
                $task->update($request->all());
        
                if ($request->ajax()) 
                {
                    return response()->json(['success'=>'Task updated successfully!', 'task' => $task]);
                }
                return redirect()->route('task.index');
            } catch (\Exception $e) 
            {
                return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
            }
        }
        return response()->json(['error' => $validator->errors()->all()]);
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
