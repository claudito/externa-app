<?php
namespace App\Repositories;

use App\Models\Order;

interface TaskRepositoryInterface
{   
    public function index($user_id);
    public function create(array $data);
    public function delete($task_id);
    public function update($task_id,array $data);
    public function show($task_id);
}