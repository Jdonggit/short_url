<?php

namespace App\Repositories;

use App\Models\Visitor;

class VisitorRepository extends BaseRepository
{
    protected function model() {
        return Visitor::class;
    }
}