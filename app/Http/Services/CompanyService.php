<?php

namespace App\Http\Services;

use App\Traits\CanCRUDUsingRepository;

class CompanyService
{
    use CanCRUDUsingRepository;
    public function __construct(private \App\Http\Repositories\CompanyRepository $repository){}
}