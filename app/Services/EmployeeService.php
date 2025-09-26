<?php

namespace App\Services;

use App\Mail\NewEmployeeNotificationMail;
use App\Models\Employee;
use App\Traits\CanCRUDUsingRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class EmployeeService
{
    use CanCRUDUsingRepository;
    public function __construct(private \App\Repositories\EmployeeRepository $repository){}

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

    public function create(array $data)
    {
        $data = $this->repository->create($data);
        Mail::to($data->company->email)->queue(new NewEmployeeNotificationMail($data));
        return $data;
    }

}