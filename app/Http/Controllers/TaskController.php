<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    //
    private $taskRepository;

    public function __construct(TaskRepositoryInterface  $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $result = $this->taskRepository->index($user->id);
            return ['data' => $result];
        }
        return view('tasks', [
            'user' => Auth::user(),
            'estados' => Status::get()
        ]);
    }

    public function store(Request $request)
    {
        if (is_null($request->id)) {
            return $this->taskRepository->create($request->toArray());
        } else {
            return $this->taskRepository->update($request->id, $request->toArray());
        }
    }

    public function delete($task_id)
    {
        return $this->taskRepository->delete($task_id);
    }

    public function show($task_id)
    {
        return $this->taskRepository->show($task_id);
    }
}
