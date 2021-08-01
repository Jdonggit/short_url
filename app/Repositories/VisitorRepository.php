<?php

namespace App\Repositories;

use App\Models\Visitor;

class VisitorRepository extends BaseRepository
{
    protected function model() {
        return Visitor::class;
    }

    public function getVisitorIp($url)
    {
        return $this->model()::where('ip', request()->ip())->where('short_url',$url)->first();
    }
}