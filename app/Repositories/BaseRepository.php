<?php

namespace App\Repositories;

use Illuminate\Container\Container as App;

abstract class BaseRepository implements IRepository
{
    private $app;
    private $modelClass;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->modelClass = $this->model();
    }

    // 回傳各別 repository 要用的 model
    protected abstract function model();

    public function create(array $data)
    {
        $newModelInstance = $this->app->make($this->modelClass);
        return $this->setModelInstance($newModelInstance, $data);
    }

    public function insert(array $data)
    {
        try {
            $this->modelClass::insert($data);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update($id, array $data)
    {
        $modelInstance = $this->modelClass::find($id);
        return $this->setModelInstance($modelInstance, $data);
    }

    protected function setModelInstance($instance, array $data = [])
    {
        if (isset($instance)) {
            foreach($data as $property => $value) {
                if (isset($value)) {
                    $instance[$property] = $value;
                }
            }

            $saveSuccess = $instance->save();
            if ($saveSuccess) {
                return $instance;
            }
        }
        throw new \Exception("create/update failed");
    }

    public function delete($id)
    {
        $this->modelClass::destroy($id);
    }

    public function find($id, $columns = ['*'])
    {
        return $this->modelClass::find($id, $columns);
    }

}
