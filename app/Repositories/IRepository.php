<?php

namespace App\Repositories;

interface IRepository
{
    public function find($id);

    public function insert(array $data);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}