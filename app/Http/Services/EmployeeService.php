<?php

namespace App\Http\Services;

use App\Models\Employee;
use App\Traits\CanCRUDUsingRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeService
{
    use CanCRUDUsingRepository;
    public function __construct(private \App\Http\Repositories\EmployeeRepository $repository){}

    public function paginate(array $filters = []) : LengthAwarePaginator
    {
         $query = Employee::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        return $this->repository->paginate($filters, $query, ['company']);
    }
}