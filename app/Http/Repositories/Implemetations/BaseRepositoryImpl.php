<?php

namespace App\Http\Repositories\Implemetations;

use Illuminate\Database\Eloquent\Model;
use App\Http\Repositories\BaseRepository;

abstract class BaseRepositoryImpl implements BaseRepository
{
    public function __construct(private Model $model) {}

    public function paginate(array $filters = [], $query = null, array $with = [])
    {
        $perPage = $filters['per_page'] ?? 10;
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query = $query ?? $this->model->newQuery();
        return $query
            ->with($with)
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);
    }

    public function findById($id, array $with = []): ?Model
    {
        return $this->model->with($with)->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): bool
    {
        $record = $this->model->query()->findOrFail($id);
        if ($record) {
            return $record->update($data);
        }
        return false;
    }

    public function delete($id): bool
    {
        $record = $this->model->query()->findOrFail($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
