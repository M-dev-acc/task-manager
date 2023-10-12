<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Exceptions\HttpResponseException;
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
        $tasksCollection = Task::latest()->get();

        return response()->json([
            'status' => true,
            'message' => "Tasks list",
            'tasks' => TaskResource::collection($tasksCollection),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTaskRequest $request)
    {
        $validatedInput = $request->validated();

        try {
            Task::create([
                'name' => $validatedInput['name'],
                'is_completed' => false,
            ]);

            return response()->json([
                'status' => true,
                'message' => "New task added!",
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => "Something went wrong!",
            ], 500));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return response()->json([
            'status' => true,
            'message' => "Task data",
            'task' => new TaskResource($task),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validatedInputs = $request->validated();

        try {
            $task->update([
                'name' => $validatedInputs['name'],
                'is_completed' => $validatedInputs['status'],
            ]);

            return response()->json([
                'status' => true,
                'message' => "Task data has been updated.",
            ], 200);
        } catch (\Throwable $th) {
            throw $th;
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => "Something went wrong!",
            ], 500));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();

            return response()->json([
                'status' => true,
                'message' => "Task data deleted",
            ], 200);
        } catch (\Throwable $th) {
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => "Something went wrong!",
            ], 500));
        }
    }
}
