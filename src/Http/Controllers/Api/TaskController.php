<?php

namespace sh0beir\todo\Http\Controllers\Api;

use sh0beir\todo\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use sh0beir\todo\Http\Controllers\Controller;
use sh0beir\todo\Http\Resources\TaskResource;
use sh0beir\todo\Models\Label;
use sh0beir\todo\Notifications\CloseTask;

class TaskController extends Controller
{


    public function index()
    {
        $data = auth()->user()->tasks()->with(['labels' => function ($query) {
            $query->withCount(['tasks' => function ($query) {
                $query->where('author_id', auth()->user()->id);
            }]);
        }])->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // filter tasks for auth user by label 
    public function filter(Label $label)
    {
        $data = auth()->user()->tasks()->whereHas('labels', function ($query) use ($label) {
            $query->where('label_id', $label->id);
        })->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function show(Task $task)
    {
        //check auther of task
        if ($task->author_id != auth()->user()->id) {
            return response()->json([
                'message' => 'You are not the author of this task'
            ], 403);
        }

        return (new TaskResource($task));
    }

    public function store(Request $request)
    {

        request()->validate([
            'title' => 'required',
            'description'  => 'required',
        ]);

        // Assume the authenticated user is the post's author
        $author = auth()->user();

        $task = $author->tasks()->create([
            'title'     => $request->title,
            'description'     => $request->description,
        ]);

        return response()->json([
            'data' => $task
        ], 201);
    }

    public function update(Task $task, Request $request)
    {

        // Let's assume we need to be authenticated
        // to update a post
        // if (!auth()->check()) {
        //     abort(403, 'Only authenticated users can update tasks.');
        // }

        request()->validate([
            'title' => 'required',
            'description'  => 'required',
        ]);

        $task->update([
            'title'     => $request->title,
            'description'     => $request->description,
        ]);

        return response()->json([
            'data' => $task
        ], 200);
    }

    public function changeStatus(Task $task, Request $request)
    {
        //check auther of tast
        if ($task->author_id != auth()->user()->id) {
            return response()->json([
                'message' => 'You are not the author of this task'
            ], 403);
        }

        $task->update([
            'status' => $request->status == "true" ? true : false,
        ]);

        //if status == true notification send to user
        if ($request->status == "true") {
            auth()->user()->notify(new CloseTask($task));
        }
        return response()->json([
            'data' => $task
        ], 200);
    }

    //attach multie labels to task
    public function attach(Task $task, Request $request)
    {
        //check auther of task
        if ($task->author_id != auth()->user()->id) {
            return response()->json([
                'message' => 'You are not the author of this task'
            ], 403);
        }

        $task->labels()->attach($request->labels);

        return response()->json([
            'labels' => $task->labels
        ], 200);
    }

    // public function attachLabel(Task $task, Label $label)
    // {
    //     //check auther of tast
    //     if ($task->author_id != auth()->user()->id) {
    //         return response()->json([
    //             'message' => 'You are not the author of this task'
    //         ], 403);
    //     }
    //     $task->labels()->attach($label);

    //     return response()->json([
    //         'data' => $task
    //     ], 200);
    // }

    // public function delete(Task $task)
    // {
    //     $task->delete();
    //     return response()->json(['message' => 'Task deleted successfully.']);
    // }
}
