<?php

namespace App\Http\Services;

use App\Traits\CanCRUDUsingRepository;

class EmployeeService
{
    use CanCRUDUsingRepository;
    public function __construct(private \App\Http\Repositories\EmployeeRepository $repository){}
}