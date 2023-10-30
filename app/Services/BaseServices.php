<?php

namespace App\Services;

class BaseServices
{
    protected $model;

    public function __call($name, $arguments)
    {
        $this->model->{$name}($arguments);
    }

    public function setModel($model): BaseServices
    {
        $this->model = $model;
        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getModelById($id, array $with = [])
    {
        if (count($with)){
            return $this->model
                ->newQuery()
                ->with($with)
                ->where('id', $id)
                ->first();
        }else{
            return $this->model
                ->newQuery()
                ->where('id', $id)
                ->first();
        }
    }
}
