<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\TaskRepositoryInterface;

class ApiTaskController extends Controller
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface  $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index($user_id)
    {
        $result = $this->taskRepository->index($user_id);
        return $result;
    }

    public function create(Request $request)
    {
        return $this->taskRepository->create($request->toArray());
    }

    public function update($task_id, Request $request)
    {
        return $this->taskRepository->update($task_id, $request->toArray());
    }

    public function delete($task_id)
    {
        return $this->taskRepository->delete($task_id);
    }
}
