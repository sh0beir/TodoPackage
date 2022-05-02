<?php

namespace sh0beir\todo\Http\Controllers\Api;

use sh0beir\todo\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use sh0beir\todo\Http\Controllers\Controller;
use sh0beir\todo\Http\Resources\LabelResource;

class LabelController extends Controller
{

    // 
    public function index()
    {

        $labels = Label::withCount(['tasks' => function ($query) {
            $query->where('author_id', auth()->user()->id);
        }])->get();
        return LabelResource::collection($labels)->response()->setStatusCode(200);
    }

    public function show(Label $label)
    {
        $data = $label->loadCount(['tasks' => function ($query) {
            $query->where('author_id', auth()->user()->id);
        }]);

        //return all labels with tasks where tasks.auther_id = auth()->user()->id
        return (new LabelResource($data));
    }

    public function store(Request $request)
    {

        // Let's assume we need to be authenticated
        // to create a new post
        // if (!auth()->check()) {
        //     abort(403, 'Only authenticated users can create new label.');
        // }

        $validator = Validator::make($request->all(), [
            'label' => 'required|unique:labels',
        ]);

        // If the validator fails, it will return an array of errors
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $label = Label::create([
            'label'     => $request->label,
        ]);

        return response()->json([
            'data' => $label
        ], 201);
    }


    // public function update(Label $label, Request $request)
    // {

    //     // Let's assume we need to be authenticated
    //     // to update a post
    //     // if (!auth()->check()) {
    //     //     abort(403, 'Only authenticated users can update labels.');
    //     // }

    //     request()->validate([
    //         'label' => 'required',
    //     ]);

    //     $label->update([
    //         'label'     => $request->label,
    //     ]);

    //     return response()->json([
    //         'data' => $label
    //     ], 200);
    // }

    // public function delete(Label $label)
    // {
    //     $label->delete();
    //     return response()->json(['message' => 'Label deleted successfully.']);
    // }
}
