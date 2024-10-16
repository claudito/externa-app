<?php

namespace App\Http\Controllers;

use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //
    private $taskRepository;

    public function __construct(TaskRepositoryInterface  $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(Request $request) {
        $user_id = 1;
        $result = $this->taskRepository->index($user_id);
        return $result;
    }

    public function create(Request $request) {}

    public function update(Request $request) {}

    public function show(Request $request) {}
}
