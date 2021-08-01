<?php

namespace App\Services;

use App\Repositories\IRepository;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService implements IService
{
    protected $repository;
    protected $resourceClass;

    public function __construct(IRepository $repository)
    {
        // 設定主要進行 CRUD 的 repository
        $this->repository = $repository;
    }

    // ...

    public function update(Model $modelInstance, array $data)
    {
        // 我們將 IRepository 的 update function 改為可以接收
        // Model 實例或是流水號 "id"
        return $this->repository->update($modelInstance, $data);
    }
}