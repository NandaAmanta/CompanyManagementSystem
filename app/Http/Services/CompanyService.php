<?php

namespace App\Http\Services;

use App\Models\Company;
use App\Traits\CanCRUDUsingRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class CompanyService
{
    use CanCRUDUsingRepository;
    public function __construct(private \App\Http\Repositories\CompanyRepository $repository){}

    public function create(array $data)
    {
        if(isset($data['logo'])) {
            $data['logo_path'] = Storage::disk('public')->put('company_logos', $data['logo']);
            unset($data['logo']);
        }else{
            $data['logo_path'] = null;
        }
        return $this->repository->create($data);
    }

    public function update($id, array $data): bool
    {
        if(isset($data['logo'])) {
            $data['logo_path'] = Storage::disk('public')->put('company_logos', $data['logo']);
            unset($data['logo']);
        }
        return $this->repository->update($id, $data);
    }

    public function paginate(array $filters = []) : LengthAwarePaginator
    {
        $query = Company::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('website', 'like', "%{$search}%");
            });
        return $this->repository->paginate($filters, $query);
    }
}