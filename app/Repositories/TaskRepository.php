<?php

namespace App\Repositories;

use App\Models\Status;
use App\Models\Task;
use Carbon\Carbon;

class TaskRepository implements TaskRepositoryInterface
{
    public function index($user_id)
    {
        $result = Task::selectRaw("
            tasks.id,
            tasks.title as titulo,
            tasks.description as descripcion,
            tasks.last_date as fecha_vencimiento,
            statuses.name estado
        ")
            ->join('statuses', function ($join) {
                $join->on('tasks.status_id', '=', 'statuses.id');
            })
            ->where('user_id', $user_id)->get();
        return $result;
    }
    public function create(array $data)
    {
        try {

            $this->isValid($data);

            $last_date = Carbon::parse($data['last_date']);
            $task = Task::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'last_date' => $last_date,
                'user_id' => $data['user_id']
            ]);

            return response()->json([
                'code' => 0,
                'status' => 'success',
                'message' => 'Registro Creado: ' . $task->id
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 0,
                'status' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function update($task_id, array $data)
    {
        try {

            $this->isValid($data);

            if (!Task::where('id', $task_id)->exists()) {
                throw new \Exception('La Tarea '. $task_id .' No Existe');
            }


            if (!Status::where('id',  $data['status_id'])->exists()) {
                throw new \Exception('La Estado No Existe');
            }

            if (!isset($data['status_id'])) {
                throw new \Exception('El campo Estado es requerido');
            }


            $last_date = Carbon::parse($data['last_date']);
            Task::where('id', $task_id)->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'last_date' => $last_date,
                'status_id' => $data['status_id']
            ]);

            return response()->json([
                'code' => 0,
                'status' => 'success',
                'message' => 'Registro Actualizado'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 0,
                'status' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }

    public function delete($task_id)
    {
        try {

            if (!Task::where('id', $task_id)->exists()) {
                throw new \Exception('La Tarea '. $task_id .' No Existe');
            }

            Task::where('id', $task_id)->delete();
            return response()->json([
                'code' => 0,
                'status' => 'success',
                'message' => 'Registro Eliminado'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 0,
                'status' => 'error',
                'message' => $e->getMessage()
            ], 200);
        }
    }


    private function isValid($data)
    {

        if (!isset($data['title'])) {
            throw new \Exception('El campo Título es requerido');
        }

        if (!isset($data['description'])) {
            throw new \Exception('El campo Descripción es requerido');
        }

        if (!isset($data['last_date'])) {
            throw new \Exception('El campo Fecha de Vencimiento es requerido');
        }

        if (!strtotime($data['last_date'])) {
            throw new \Exception('El campo Fecha de Vencimiento debe ser una fecha válida');
        }
    }
}
