<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use PDOException;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $allTasks = Task::all();
        return response()->json([
            'status' => 1,
            'tasks' => $allTasks
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        try {

            $data = $request->all();
            $data['user_id'] = 1;
            $task = Task::create($data);
            return response()->json([
                'status' => 1,
                'message'=> 'Task created successfully',
                'task' => $task
            ], 201);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Task $task)
    {
        return response()->json([
            'status' => 1,
            'message' => 'Here is the task you looked for',
            'task' => $task
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreTaskRequest $request, Task $task)
    {
        try {

            $data = $request->all();
            $data['user_id'] = 1;
            $task->update($data);

            return response()->json([
                'status' => 1,
                'message'=> 'Task updated successfully',
                'task' => $task,
            ], 200);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        try{
            $task->delete();

            return response()->json([
                'status' => 1,
                'message' => "Task deleted successfully"
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
