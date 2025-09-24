<?php

namespace App\Http\Repositories\Implemetations;

use App\Http\Repositories\EmployeeRepository;
use App\Models\Employee;

class EmployeeRepositoryImpl extends BaseRepositoryImpl implements EmployeeRepository
{
    public function __construct(private Employee $model)
    {
        parent::__construct($model);
    }
}
