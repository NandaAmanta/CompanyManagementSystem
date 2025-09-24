<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait CanCRUDUsingRepository
{
    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    public function detail($id, array $with = [])
    {
        return $this->repository->findById($id, $with);
    }

    public function paginate(array $filters = []) : LengthAwarePaginator
    {
        return $this->repository->paginate($filters);
    }
}
