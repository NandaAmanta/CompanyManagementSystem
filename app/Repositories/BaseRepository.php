<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface BaseRepository
{
    public function paginate(array $filters = [], $query = null , array $with = []);

    public function findById($id, array $with = []): ?Model;

    public function create(array $data): Model;

    public function update($id, array $data): bool;

    public function delete($id): bool;

}