<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

interface IService
{
    // ...
    public function update(Model $modelInstance, array $data);
    // ...
}